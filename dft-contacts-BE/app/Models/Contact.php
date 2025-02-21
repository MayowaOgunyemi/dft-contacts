<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;
        
    /**
     * fillable
     * Description: The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fname', 'lname', 'email', 'phone', 'address'];
}
