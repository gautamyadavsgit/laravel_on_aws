<?php

namespace Tests\Feature;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tests\DatabaseTestCase;

class PropertyPaginationTest extends DatabaseTestCase
{
    public function test_admin_properties_screen_is_paginated(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.properties.index'));

        $response->assertStatus(200);
        $response->assertViewHas('properties');

        $paginator = $response->viewData('properties');
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
