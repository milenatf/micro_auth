<?php

namespace App\Models\EmailVerify;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'token', 'expired_at'];
}
