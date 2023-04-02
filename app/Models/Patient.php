<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_patient',
        'name_mother', 
        'date_birth', 
        'name', 
        'cpf', 
        'cns'
    ];

    protected $dates = ['date_birth'];

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function setDateBirthAttribute($value)
    {
        $this->attributes['date_birth'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getDateBirthAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }
}
