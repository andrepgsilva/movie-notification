<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property ?string $synopsis
 * @property ?string $genre
 * @property ?string $release_date
 * @property ?string $metascore
 * @property ?string $cover_url
 * @property ?string $trailer_url
 * @property bool $available
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 */
class Movie extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'synopsis',
        'genre',
        'release_date',
        'metascore',
        'cover_url',
        'trailer_url',
        'available',
        'slug',
    ];
}
