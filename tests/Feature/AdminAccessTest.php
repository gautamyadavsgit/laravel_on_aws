<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tests\DatabaseTestCase;

class AdminAccessTest extends DatabaseTestCase
{
    public function test_guests_and_investors_cannot_reach_admin_endpoints(): void
    {
        $property = $this->property();
        $image = $property->images()->create(['property_image_key' => 'property_image', 'property_image_value' => 'property_images/x.png']);
        $investor = $this->investor();

        $gets = [
            '/admin/dashboard',
            '/admin/users',
            '/admin/users/create',
            "/admin/users/{$investor->id}",
            "/admin/users/{$investor->id}/edit",
            '/admin/properties',
            '/admin/properties/create',
            "/admin/properties/{$property->id}",
            "/admin/properties/{$property->id}/edit",
        ];

        foreach ([null, $investor] as $actor) {
            foreach ($gets as $url) {
                $request = $actor ? $this->actingAs($actor, 'web') : $this;
                $request->get($url)->assertRedirect('/');
            }
        }

        $posts = [
            ['post', '/admin/users', ['first_name' => 'X', 'email' => 'x@example.com', 'password' => 'password123']],
            ['put', "/admin/users/{$investor->id}", ['first_name' => 'Hacked', 'email' => $investor->email]],
            ['delete', "/admin/users/{$investor->id}", []],
            ['post', '/admin/properties', ['name' => 'x']],
            ['put', "/admin/properties/{$property->id}", ['name' => 'Hacked']],
            ['delete', "/admin/properties/{$property->id}", []],
            ['delete', "/admin/properties/{$property->id}/images/{$image->id}", []],
        ];

        foreach ($posts as [$method, $url, $body]) {
            $this->actingAs($investor, 'web')->{$method}($url, $body)->assertRedirect('/');
        }

        $this->assertDatabaseHas('properties', ['id' => $property->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('users', ['id' => $investor->id, 'first_name' => 'Audit']);
    }

    public function test_admin_login_validates_input(): void
    {
        $this->from('/admin')->post('/admin/login', [])->assertSessionHasErrors(['email', 'password']);
        $this->from('/admin')->post('/admin/login', ['email' => 'nope', 'password' => 'x'])->assertSessionHasErrors('email');
        $this->from('/admin')->post('/admin/login', ['email' => ['a@b.co'], 'password' => ['x']])->assertSessionHasErrors(['email', 'password']);
        $this->from('/admin')->post('/admin/login', ['email' => 'nobody@example.com', 'password' => 'wrong'])
            ->assertRedirect('/admin');
        $this->assertGuest('admin');
    }

    public function test_admin_login_is_rate_limited(): void
    {
        $email = 'audit_admin_rl@example.com';
        RateLimiter::clear(Str::transliterate($email.'|127.0.0.1'));

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => $email, 'password' => 'wrong']);
        }

        Admin::create(['username' => 'rl', 'email' => $email, 'password' => Hash::make('password123')]);
        $this->post('/admin/login', ['email' => $email, 'password' => 'password123']);

        $this->assertGuest('admin');
        RateLimiter::clear(Str::transliterate($email.'|127.0.0.1'));
    }
}
