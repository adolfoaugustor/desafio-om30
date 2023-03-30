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

    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
