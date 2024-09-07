<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kelas',
        'motto_hidup',
        'alasan_masuk',
        'image',
    ];

    public function user() 
    {
        return $this->hasMany(User::class);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('/storage/pendaftar/' . $image),
        );
    }
}
