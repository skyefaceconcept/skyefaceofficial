<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SeoMeta;
use Illuminate\Support\Facades\Queue;
use App\Jobs\PingSearchEngines;

class SeoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_seo_entry_and_dispatch_ping_job()
    {
        Queue::fake();

        // Create role + superadmin user
        $role = \App\Models\Role::create(['name' => 'SuperAdmin', 'slug' => 'superadmin']);
        $user = \App\Models\User::factory()->create(['role_id' => $role->id]);

        $this->actingAs($user);

        $response = $this->post(route('admin.seo.store'), [
            'page_slug' => 'about',
            'title' => 'About us',
            'meta_description' => 'About Skyeface',
        ]);

        $this->assertDatabaseHas('seo_meta', ['page_slug' => 'about', 'meta_description' => 'About Skyeface']);

        Queue::assertPushed(PingSearchEngines::class);
    }

    public function test_setting_site_default_unsets_previous_defaults()
    {
        SeoMeta::create(['page_slug' => 'page-1', 'meta_description' => 'A', 'is_site_default' => false]);
        SeoMeta::create(['page_slug' => null, 'meta_description' => 'Default', 'is_site_default' => true]);

        $role = \App\Models\Role::create(['name' => 'SuperAdmin', 'slug' => 'superadmin']);
        $user = \App\Models\User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $this->post(route('admin.seo.store'), [
            'is_site_default' => '1',
            'meta_description' => 'New default',
        ]);

        $this->assertDatabaseHas('seo_meta', ['meta_description' => 'New default', 'is_site_default' => true]);
        $this->assertDatabaseMissing('seo_meta', ['meta_description' => 'Default', 'is_site_default' => true]);
    }
}
