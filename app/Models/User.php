<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canJoinGroup(int $userId): bool
    {
    return $this->id == $userId;
    }

     public function notif(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
    
           public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }
      public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Get the chats where the user is the recipient.
     */
    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'recipient_id');
    }
    public function chats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }
}
