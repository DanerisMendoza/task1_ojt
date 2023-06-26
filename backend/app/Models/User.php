<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    // use HasFactory;
    protected $table = 'user_tb';
    protected $fillable = ['username', 'password','role'];
    protected $primaryKey = 'user_id';

}
