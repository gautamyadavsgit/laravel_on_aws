<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\PropertyInterest;
use App\Models\User;
use Tests\DatabaseTestCase;

/**
 * Properties are soft deleted, but investors' favorites / interests keep pointing at them.
 */
class SoftDeletedPropertyTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->investor();
        $this->admin = $this->admin();
        $this->property = $this->property();

        PropertyFavorite::create(['user_id' => $this->user->id, 'property_id' => $this->property->id]);
        PropertyInterest::create(['user_id' => $this->user->id, 'property_id' => $this->property->id, 'status' => 'new']);

        $this->property->delete();
    }

    protected User $user;

    protected Property $property;

    protected Admin $admin;

    public function test_investor_dashboard_survives_a_deleted_property(): void
    {
        $this->actingAs($this->user)->get('/dashboard')->assertOk();
    }

    public function test_admin_user_pages_survive_a_deleted_property(): void
    {
        $this->actingAs($this->admin, 'admin')->get("/admin/users/{$this->user->id}")->assertOk();
        $this->actingAs($this->admin, 'admin')->get('/admin/users')->assertOk();
    }

    public function test_favoriting_or_expressing_interest_in_a_deleted_property_is_not_found(): void
    {
        $this->actingAs($this->user)->postJson("/property/{$this->property->id}/favorite")->assertNotFound();
        $this->actingAs($this->user)->post("/investment-interest/{$this->property->id}")->assertNotFound();
    }
}
