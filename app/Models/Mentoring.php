<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_mentoring',
        'nama_mentor',
        'tempat_mentoring',
        'materi_singkat',
        'image',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('/storage/mentoring/' . $image),
        );
    }
}