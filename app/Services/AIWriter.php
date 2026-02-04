<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIWriter
{
    protected string $provider;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->provider = env('AI_PROVIDER', 'openai');
        $this->apiKey = env('AI_API_KEY');
    }

    /**
     * Generate SEO fields for a page
     * returns array with keys: title, meta_description, meta_keywords, og_title, og_description, twitter_title, twitter_description, json_ld
     */
    public function generateForPage(string $pageSlug, ?string $context = '', string $tone = 'natural', int $max_tokens = 256): array
    {
        // Normalize null -> empty string to be type-safe
        $context = $context ?? '';
        // Attempt a two-pass call: first gentle prompt, second strict 'use only content' pass if needed
        return $this->callOpenAiWithDetails($pageSlug, $context, $tone, $max_tokens);
    }

    /**
     * Build system & user messages for preview or for calls
     */
    public function buildMessages(string $pageSlug, ?string $context = '', string $tone = 'natural', bool $strict = false): array
    {
        $context = $context ?? '';
        if (! $strict) {
            $system = "You are an expert SEO copywriter. Produce output as JSON with keys: title, meta_description, meta_keywords (comma separated), og_title, og_description, twitter_title, twitter_description, json_ld. Prioritize and use the provided page content and existing SEO fields to create improved title, description, canonical and other metadata. Make title <= 60 chars and meta_description <= 160 chars. Return valid JSON only (no extra commentary). If context is 'None' or empty, infer content from the page slug. Do not repeat these instructions in the output.";
            $user = "Page slug: $pageSlug\nContext: " . ($context ?: 'None') . "\nTone: $tone";
        } else {
            $system = "You are an expert SEO copywriter. DO NOT REPEAT ANY INSTRUCTIONS. USE ONLY THE PAGE CONTENT between the markers in the user message. Produce output as JSON with keys: title, meta_description, meta_keywords (comma separated), og_title, og_description, twitter_title, twitter_description, json_ld. Make title <= 60 chars and meta_description <= 160 chars. Return valid JSON ONLY (no extra commentary or preface).";
            $user = "---BEGIN PAGE CONTENT---\n" . ($context ?: 'None') . "\n---END PAGE CONTENT---\nPage slug: $pageSlug\nTone: $tone";
        }

        return ['system' => $system, 'user' => $user];
    }

    protected function buildPrompt(string $pageSlug, ?string $context, string $tone): string
    {
        $instructions = "You are an expert SEO copywriter. Produce output as JSON with keys: title, meta_description, meta_keywords (comma separated), og_title, og_description, twitter_title, twitter_description, json_ld. " .
            "Write concise human-sounding metadata for the page identified as '{page_slug}'. Use a friendly, professional tone. Make title <= 60 chars and meta_description <= 160 chars. Make OG and Twitter descriptions similar to meta_description but more engaging. Include basic JSON-LD for a WebPage with name and description. Always return valid JSON only (no extra commentary).";

        $prompt = str_replace('{page_slug}', $pageSlug, $instructions) . "\nContext: " . ($context ?: 'None') . "\nTone: $tone";
        return $prompt;
    }

    protected function callProvider(string $prompt, int $max_tokens = 256): array
    {
        if ($this->provider === 'openai') {
            return $this->callOpenAi($prompt, $max_tokens);
        }

        // Fallback: generate simple heuristics
        return $this->fallback($prompt);
    }

    protected function callOpenAi(string $prompt, int $max_tokens = 256): array
    {
        if (! $this->apiKey) {
            return $this->fallback($prompt);
        }

        // Try to extract pageSlug/context/tone from the prompt if present
        $pageSlug = 'page'; $context = ''; $tone = 'natural';
        if (preg_match('/Page slug: (.*?)\\nContext: (.*?)\\nTone: (.*)/s', $prompt, $m)) {
            $pageSlug = $m[1] ?? 'page';
            $context = $m[2] ?? '';
            $tone = $m[3] ?? 'natural';
        }

        try {
            // First pass: standard system + concise user message
            $systemInstructions = "You are an expert SEO copywriter. Produce output as JSON with keys: title, meta_description, meta_keywords (comma separated), og_title, og_description, twitter_title, twitter_description, json_ld. Prioritize and use the provided page content and existing SEO fields to create improved title, description, canonical and other metadata. Make title <= 60 chars and meta_description <= 160 chars. Return valid JSON only (no extra commentary). If context is 'None' or empty, infer content from the page slug. Do not repeat these instructions in the output.";

            $userMessage = "Page slug: $pageSlug\nContext: " . ($context ?: 'None') . "\nTone: $tone";

            \Log::debug('AIWriter: openai request (pass1)', ['user' => substr($userMessage,0,400), 'model' => env('AI_MODEL', 'gpt-3.5-turbo')]);

            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => env('AI_MODEL', 'gpt-3.5-turbo'),
                    'messages' => [
                        ['role' => 'system', 'content' => $systemInstructions],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'max_tokens' => max(256, $max_tokens),
                    'temperature' => 0.2,
                    'n' => 1,
                ]);

            $json = $response->json();
            // If the API returned an error payload, surface it as an exception so controller can inform the user
            if (isset($json['error']) && isset($json['error']['type'])) {
                $errType = $json['error']['type'];
                $errMsg = $json['error']['message'] ?? 'OpenAI error';
                \Log::warning('AIWriter: openai returned error (pass1)', ['type' => $errType, 'message' => $errMsg]);
                if ($errType === 'insufficient_quota') {
                    throw new \RuntimeException('OpenAI error: insufficient quota. ' . $errMsg);
                }
                throw new \RuntimeException('OpenAI error: ' . $errMsg);
            }

            $content = data_get($json, 'choices.0.message.content') ?? data_get($json, 'choices.0.text');
            if (! $content) {
                \Log::warning('AIWriter: openai returned no content (pass1)', ['resp' => array_slice($json,0,5)]);
                $content = '';
            }

            \Log::debug('AIWriter: openai response snippet (pass1)', ['snippet' => substr(trim($content),0,600)]);

            // If the model echoed instruction or returned no JSON, perform a strict second pass
            $echoed = $content && (str_starts_with(trim($content), 'You are an expert') || str_contains(trim($content), 'Produce output as JSON'));

            $decoded = null;
            if (! $echoed && $content) {
                $maybe = trim($content);
                if (preg_match('/\{.*\}/s', $maybe, $matches)) {
                    $decoded = json_decode($matches[0], true);
                }
            }

            if (is_array($decoded)) {
                return $decoded;
            }

            // Second pass: strict 'use only the content' prompt, deterministic
            \Log::debug('AIWriter: openai retrying with strict content-only pass (pass2)', ['page' => $pageSlug]);
            $systemInstructions2 = "You are an expert SEO copywriter. DO NOT REPEAT ANY INSTRUCTIONS. USE ONLY THE PAGE CONTENT between the markers in the user message. Produce output as JSON with keys: title, meta_description, meta_keywords (comma separated), og_title, og_description, twitter_title, twitter_description, json_ld. Make title <= 60 chars and meta_description <= 160 chars. Return valid JSON ONLY (no extra commentary or preface).";

            $userMessage2 = "---BEGIN PAGE CONTENT---\n" . ($context ?: 'None') . "\n---END PAGE CONTENT---\nPage slug: $pageSlug\nTone: $tone";

            $response2 = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => env('AI_MODEL', 'gpt-3.5-turbo'),
                    'messages' => [
                        ['role' => 'system', 'content' => $systemInstructions2],
                        ['role' => 'user', 'content' => $userMessage2],
                    ],
                    'max_tokens' => max(512, $max_tokens),
                    'temperature' => 0.0,
                    'n' => 1,
                ]);

            $json2 = $response2->json();
            if (isset($json2['error']) && isset($json2['error']['type'])) {
                $errType2 = $json2['error']['type'];
                $errMsg2 = $json2['error']['message'] ?? 'OpenAI error';
                \Log::warning('AIWriter: openai returned error (pass2)', ['type' => $errType2, 'message' => $errMsg2]);
                if ($errType2 === 'insufficient_quota') {
                    throw new \RuntimeException('OpenAI error: insufficient quota. ' . $errMsg2);
                }
                throw new \RuntimeException('OpenAI error: ' . $errMsg2);
            }

            $content2 = data_get($json2, 'choices.0.message.content') ?? data_get($json2, 'choices.0.text');

            \Log::debug('AIWriter: openai response snippet (pass2)', ['snippet' => substr(trim($content2),0,800)]);

            $decoded2 = null;
            if ($content2) {
                $maybe2 = trim($content2);
                if (preg_match('/\{.*\}/s', $maybe2, $matches2)) {
                    $decoded2 = json_decode($matches2[0], true);
                }
            }

            if (is_array($decoded2)) {
                return $decoded2;
            }

            \Log::warning('AIWriter: openai pass2 failed to produce JSON, falling back', ['pass1_snippet' => substr(trim($content),0,200), 'pass2_snippet' => substr(trim($content2),0,200)]);

            return $this->fallback($prompt);
        } catch (\Throwable $e) {
            \Log::error('AIWriter: openai call failed', ['error' => $e->getMessage()]);
            return $this->fallback($prompt);
        }
    }

    protected function fallback(?string $seed = null): array
    {
        // If seed looks like an instruction or is empty, fall back to site-level defaults
        $seed = $seed ?? '';
        $isInstruction = str_contains($seed, 'You are an expert') || strlen($seed) > 200;
        if ($isInstruction || trim($seed) === '') {
            $label = config('app.name') ?: 'Site';
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', ' ', $label));
        } else {
            // Try to normalize a slug-like seed
            $slug = str_replace(['-', '_'], ' ', trim($seed));
            $slug = substr($slug, 0, 60);
        }

        $title = ucfirst(substr($slug, 0, 50));
        $description = substr("Learn about $slug on " . (config('app.name') ?: 'our site'), 0, 160);

        \Log::warning('AIWriter: using fallback metadata', ['seed_sample' => substr($seed,0,120), 'title' => $title]);

        return [
            'title' => $title,
            'meta_description' => $description,
            'meta_keywords' => implode(', ', array_slice(explode(' ', $slug), 0, 10)),
            'og_title' => $title,
            'og_description' => $description,
            'twitter_title' => $title,
            'twitter_description' => $description,
            'json_ld' => json_encode(['@context' => 'https://schema.org', '@type' => 'WebPage', 'name' => $title, 'description' => $description]),
        ];
    }
}
