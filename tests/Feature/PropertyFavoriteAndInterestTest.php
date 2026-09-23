<?php

namespace Tests\Feature;

use App\Models\PropertyFavorite;
use App\Models\PropertyInterest;
use Tests\DatabaseTestCase;

class PropertyFavoriteAndInterestTest extends DatabaseTestCase
{
    public function test_favorite_toggle_requires_authentication(): void
    {
        $property = $this->property();

        $this->postJson("/property/{$property->id}/favorite")->assertUnauthorized();
    }

    public function test_favorite_toggle_handles_missing_property_and_toggles_cleanly(): void
    {
        $user = $this->investor();
        $property = $this->property();

        $this->actingAs($user)->postJson('/property/99999999/favorite')->assertNotFound();
        $this->actingAs($user)->postJson('/property/not-a-number/favorite')->assertNotFound();

        $this->actingAs($user)->postJson("/property/{$property->id}/favorite")
            ->assertOk()->assertJson(['success' => true, 'isFavorited' => true]);
        $this->actingAs($user)->postJson("/property/{$property->id}/favorite")
            ->assertOk()->assertJson(['success' => true, 'isFavorited' => false]);

        $this->assertDatabaseCount('property_favorites', PropertyFavorite::count());
        $this->assertDatabaseMissing('property_favorites', ['user_id' => $user->id, 'property_id' => $property->id]);
    }

    public function test_investment_interest_requires_login_and_valid_property(): void
    {
        $property = $this->property();

        $this->post("/investment-interest/{$property->id}")->assertRedirect(route('login'));

        $user = $this->investor();
        $this->actingAs($user)->post('/investment-interest/99999999')->assertNotFound();
        $this->actingAs($user)->post('/investment-interest/abc')->assertNotFound();
        $this->assertDatabaseMissing('property_interests', ['user_id' => $user->id]);
    }

    public function test_repeat_investment_interest_does_not_reset_status_or_duplicate(): void
    {
        $user = $this->investor();
        $property = $this->property();

        $this->actingAs($user)->post("/investment-interest/{$property->id}")
            ->assertRedirect(route('investment.interest'));

        PropertyInterest::where('user_id', $user->id)->update(['status' => 'contacted']);

        $this->actingAs($user)->post("/investment-interest/{$property->id}")
            ->assertRedirect(route('investment.interest'));

        $this->assertSame(1, PropertyInterest::where('user_id', $user->id)->count());
        $this->assertSame('contacted', PropertyInterest::where('user_id', $user->id)->value('status'));
    }
}
