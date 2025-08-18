<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'username',
    'phoneNumber',
    'email',
    'photo',
    'password',
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
            'password' => 'hashed',
        ];
    }
    public function skills()
    {
        return $this->hasMany(Skills::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function events()
    {
        return $this->hasManyThrough(Event::class, Organization::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function appliedEvents()
    {
        return $this->belongsToMany(Event::class, 'applications')->withTimestamps()->withPivot('status');
    }
}
