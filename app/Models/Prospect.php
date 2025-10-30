<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'website',
        'contact_person',
        'area_in_sqft',
        'email',
        'phone',
        'ext',
        'mobile',
        'address',
        'unit_number',
        'city',
        'state',
        'zip',
    ];
}
