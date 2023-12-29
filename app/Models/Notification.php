<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Group;
class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = ['message'];


     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function groups(){
        return $this->belongsTo(Group::class);
    }
}
