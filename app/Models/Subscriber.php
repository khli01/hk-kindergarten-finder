<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        "email",
        "preferred_language",
        "is_verified",
        "verified_at",
    ];

    protected $casts = [
        "is_verified" => "boolean",
        "verified_at" => "datetime",
    ];
}
