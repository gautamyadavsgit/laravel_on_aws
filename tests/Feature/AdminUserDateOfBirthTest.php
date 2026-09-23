<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminUserDateOfBirthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_date_of_birth_is_cast_and_prefilled_in_the_edit_form(): void
    {
        $admin = Admin::first() ?? Admin::factory()->create();
        $user = User::factory()->create(['dob' => '1990-05-01']);

        $this->assertSame('1990-05-01', $user->fresh()->dob->toDateString());

        $this->actingAs($admin, 'admin')->get("/admin/users/{$user->id}/edit")
            ->assertOk()
            ->assertSee('value="1990-05-01"', false);

        $this->actingAs($admin, 'admin')->get("/admin/users/{$user->id}")
            ->assertOk()
            ->assertSee('May 01, 1990');
    }

    public function test_missing_date_of_birth_renders_as_not_available(): void
    {
        $admin = Admin::first() ?? Admin::factory()->create();
        $user = User::factory()->create(['dob' => null]);

        $this->actingAs($admin, 'admin')->get("/admin/users/{$user->id}")
            ->assertOk()
            ->assertSee('N/A');
    }
}
