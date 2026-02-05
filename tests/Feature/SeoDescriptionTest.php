<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SeoMeta;

class SeoDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_description_is_injected_and_rendered()
    {
        // Create a page SEO entry for the 'about' page
        SeoMeta::create([
            'seoable_type' => 'page',
            'page_slug' => 'about',
            'title' => 'About (SEO)',
            'meta_description' => 'Custom About Page description for testing',
        ]);

        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('<meta name="description" content="Custom About Page description for testing"', false);
    }
}
