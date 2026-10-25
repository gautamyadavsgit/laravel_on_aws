<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middile_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'email_verified_at',
        'verification_link',
        'verification_token',
        'company_name',
        'alternate_phone',
        'hear_about_us',
        'experiance_level',
        'investing_reason',
        'investment_sources',
        'investing_timeline',
        'investment_goals',
        'investment_timelength',
        'accreditation_status',
        'users_net_worth',
        'address',
        'phone',
        'phone_verified',
        'app_connected',
        'dob',
        'social_security_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'dob' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's favorite properties.
     */
    /** @return HasMany<PropertyFavorite, $this> */
    public function propertyFavorites(): HasMany
    {
        return $this->hasMany(PropertyFavorite::class);
    }

    /**
     * Get the user's search history logs.
     */
    public function favoritePropertyIds(): array
    {
        return $this->propertyFavorites()->pluck('property_id')->all();
    }

    public function hasFavorited(int $propertyId): bool
    {
        return $this->propertyFavorites()->where('property_id', $propertyId)->exists();
    }

    /**
     * Get the user's property interests.
     */
    /** @return HasMany<PropertyInterest, $this> */
    public function propertyInterests(): HasMany
    {
        return $this->hasMany(PropertyInterest::class);
    }

    /**
     * Get the user's accreditation status.
     */
    /** @return BelongsTo<AccreditationStatus, $this> */
    public function accreditationStatus(): BelongsTo
    {
        return $this->belongsTo(AccreditationStatus::class, 'accreditation_status');
    }

    /**
     * Get the user's experience level.
     */
    /** @return BelongsTo<ExperienceLevel, $this> */
    public function experienceLevel(): BelongsTo
    {
        return $this->belongsTo(ExperienceLevel::class, 'experiance_level');
    }

    /**
     * Get the user's reason for investing.
     */
    /** @return BelongsTo<ReasonForInvesting, $this> */
    public function investingReason(): BelongsTo
    {
        return $this->belongsTo(ReasonForInvesting::class, 'investing_reason');
    }

    /**
     * Get the user's investment source.
     */
    /** @return BelongsTo<InvestmentSource, $this> */
    public function investmentSource(): BelongsTo
    {
        return $this->belongsTo(InvestmentSource::class, 'investment_sources');
    }

    /**
     * Get the user's investment timeline.
     */
    /** @return BelongsTo<InvestmentTimeline, $this> */
    public function investmentTimeline(): BelongsTo
    {
        return $this->belongsTo(InvestmentTimeline::class, 'investing_timeline');
    }

    /**
     * Get the user's investment goals.
     */
    /** @return BelongsTo<InvestmentGoal, $this> */
    public function investmentGoal(): BelongsTo
    {
        return $this->belongsTo(InvestmentGoal::class, 'investment_goals');
    }

    /**
     * Get the user's investment time length.
     */
    /** @return BelongsTo<InvestmentTimelength, $this> */
    public function investmentTimelength(): BelongsTo
    {
        return $this->belongsTo(InvestmentTimelength::class, 'investment_timelength');
    }

    /**
     * Get the user's net worth tier.
     */
    /** @return BelongsTo<UserNetWorth, $this> */
    public function userNetWorth(): BelongsTo
    {
        return $this->belongsTo(UserNetWorth::class, 'users_net_worth');
    }

    /**
     * Get how the user heard about the platform.
     */
    /** @return BelongsTo<HearAboutUs, $this> */
    public function hearAboutUs(): BelongsTo
    {
        return $this->belongsTo(HearAboutUs::class, 'hear_about_us');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $this->email]);
        try {
            Mail::to($this->email)->send(new ResetPasswordMail($this, $resetUrl));
        } catch (\Throwable $e) {
            Log::error('Failed to send password reset email: '.$e->getMessage());
        }
    }

    /**
     * Full name accessor
     */
    public function getNameAttribute(): string
    {
        return trim($this->first_name.' '.($this->last_name ?? ''));
    }
}
