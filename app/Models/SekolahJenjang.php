<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SekolahJenjangFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @use HasFactory<SekolahJenjangFactory> */
class SekolahJenjang extends Model
{
    use HasFactory;

    protected $table = 'sekolah_jenjang';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'order_number',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_number' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function newFactory(): SekolahJenjangFactory
    {
        return SekolahJenjangFactory::new();
    }
}

