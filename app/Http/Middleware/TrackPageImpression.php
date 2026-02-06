<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageImpression;

class TrackPageImpression
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful page views (HTML responses)
        if ($response->isSuccessful() && $this->isPageView($request, $response)) {
            try {
                // Deduplication: avoid tracking the same page multiple times in a short window (5 minutes)
                $pageUrl = $request->url();
                $dedupeKey = 'impression_' . md5($pageUrl);

                if (!session()->has($dedupeKey)) {
                    // Mark this page as just viewed
                    session()->put($dedupeKey, true, 5); // 5 minute session expiry

                    // Detect device type
                    $deviceType = $this->detectDeviceType($request);

                    PageImpression::create([
                        'route_name' => $request->route()?->getName() ?? 'unknown',
                        'page_url' => $pageUrl,
                        'page_title' => $this->getPageTitle($response),
                        'ip_address' => $request->ip(),
                        'user_agent' => substr($request->userAgent(), 0, 500),
                        'referrer' => substr($request->headers->get('referer', ''), 0, 500),
                        'user_id' => auth()->id(),
                        'device_type' => $deviceType,
                        'browser' => $this->detectBrowser($request),
                        'os' => $this->detectOS($request),
                    ]);
                }
            } catch (\Exception $e) {
                // Silently fail to avoid breaking the application
                \Log::warning('Failed to track page impression: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Check if the request is a page view (HTML response)
     */
    private function isPageView(Request $request, Response $response): bool
    {
        // Skip API routes, AJAX requests, and non-GET requests
        if ($request->isJson() || $request->expectsJson() || !$request->isMethod('GET')) {
            return false;
        }

        // Skip admin and dashboard routes
        if (strpos($request->path(), 'admin') === 0 || strpos($request->path(), 'dashboard') === 0) {
            return false;
        }

        // Check if response is HTML
        $contentType = $response->headers->get('content-type', '');
        return str_contains($contentType, 'text/html');
    }

    /**
     * Detect device type from user agent
     */
    private function detectDeviceType(Request $request): string
    {
        $userAgent = strtolower($request->userAgent());

        // Mobile patterns
        $mobilePatterns = [
            'mobile',
            'android',
            'iphone',
            'ipod',
            'windows phone',
            'blackberry',
            'opera mini',
        ];

        foreach ($mobilePatterns as $pattern) {
            if (strpos($userAgent, $pattern) !== false) {
                return 'mobile';
            }
        }

        // Tablet patterns
        $tabletPatterns = [
            'ipad',
            'tablet',
            'playbook',
            'silk',
            'android',
        ];

        foreach ($tabletPatterns as $pattern) {
            if (strpos($userAgent, $pattern) !== false) {
                return 'tablet';
            }
        }

        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    private function detectBrowser(Request $request): string
    {
        $userAgent = $request->userAgent();

        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'Opera') !== false) {
            return 'Opera';
        }

        return 'Unknown';
    }

    /**
     * Detect OS from user agent
     */
    private function detectOS(Request $request): string
    {
        $userAgent = $request->userAgent();

        if (strpos($userAgent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            return 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            return 'iOS';
        }

        return 'Unknown';
    }

    /**
     * Extract page title from response content
     */
    private function getPageTitle(Response $response): ?string
    {
        try {
            if (preg_match('/<title>(.*?)<\/title>/i', $response->getContent(), $matches)) {
                return $matches[1];
            }
        } catch (\Exception $e) {
            // Return null if unable to extract title
        }

        return null;
    }
}
