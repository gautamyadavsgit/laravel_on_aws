<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * @var array<int, string>
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's favorite properties.
     */
    public function propertyFavorites()
    {
        return $this->hasMany(PropertyFavorite::class);
    }

    /**
     * Get the user's search history logs.
     */
    public function searchLogs()
    {
        return $this->hasMany(UserSearchLog::class);
    }

    /**
     * Get the user's property interests.
     */
    public function propertyInterests()
    {
        return $this->hasMany(PropertyInterest::class);
    }

    /**
     * Get the user's accreditation status.
     */
    public function accreditationStatus()
    {
        return $this->belongsTo(AccreditationStatus::class, 'accreditation_status');
    }

    /**
     * Get the user's experience level.
     */
    public function experienceLevel()
    {
        return $this->belongsTo(ExperienceLevel::class, 'experiance_level');
    }

    /**
     * Get the user's reason for investing.
     */
    public function investingReason()
    {
        return $this->belongsTo(ReasonForInvesting::class, 'investing_reason');
    }

    /**
     * Get the user's investment source.
     */
    public function investmentSource()
    {
        return $this->belongsTo(InvestmentSource::class, 'investment_sources');
    }

    /**
     * Get the user's investment timeline.
     */
    public function investmentTimeline()
    {
        return $this->belongsTo(InvestmentTimeline::class, 'investing_timeline');
    }

    /**
     * Get the user's investment goals.
     */
    public function investmentGoal()
    {
        return $this->belongsTo(InvestmentGoal::class, 'investment_goals');
    }

    /**
     * Get the user's investment time length.
     */
    public function investmentTimelength()
    {
        return $this->belongsTo(InvestmentTimelength::class, 'investment_timelength');
    }

    /**
     * Get the user's net worth tier.
     */
    public function userNetWorth()
    {
        return $this->belongsTo(UserNetWorth::class, 'users_net_worth');
    }

    /**
     * Get how the user heard about the platform.
     */
    public function hearAboutUs()
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
