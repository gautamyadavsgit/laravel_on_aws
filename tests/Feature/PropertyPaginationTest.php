<?php

namespace Tests\Feature;

use App\Models\Admins;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class PropertyPaginationTest extends TestCase
{
    public function test_admin_properties_screen_is_paginated(): void
    {
        $admin = Admins::first() ?? Admins::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('manage-property.index'));

        $response->assertStatus(200);
        $response->assertViewHas('property');

        $paginator = $response->viewData('property');
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
        $this->assertEquals(15, $paginator->perPage());
    }

    public function test_frontend_properties_screen_is_paginated(): void
    {
        $response = $this->get('/invest');

        $response->assertStatus(200);
        $response->assertViewHas('properties');

        $paginator = $response->viewData('properties');
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
        $this->assertEquals(9, $paginator->perPage());
    }
}
