<?php

namespace Tests;

use App\Models\Admin;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Base for tests that need the real (transaction-wrapped) database plus the common record builders.
 */
abstract class DatabaseTestCase extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        config(['filesystems.default' => 'public']);
        Storage::fake('public');
        RateLimiter::clear('audit@example.com|127.0.0.1');
    }

    protected function admin(): Admin
    {
        return Admin::first() ?? Admin::factory()->create();
    }

    /**
     * Uploads built from real temp files, so MIME detection sniffs the content
     * (Laravel's fake files report a MIME type from the file name only).
     */
    protected function realUpload(string $name, string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'audit');
        file_put_contents($path, $content);

        return new UploadedFile($path, $name, null, null, true);
    }

    protected function investor(array $overrides = []): User
    {
        return User::create(array_merge([
            'first_name' => 'Audit',
            'last_name' => 'Investor',
            'email' => 'audit_'.Str::random(8).'@example.com',
            'password' => Hash::make('password123'),
        ], $overrides));
    }

    protected function property(array $overrides = []): Property
    {
        return Property::factory()->create($overrides);
    }
}
