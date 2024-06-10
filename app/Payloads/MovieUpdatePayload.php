<?php

namespace App\Payloads;

use App\Payloads\AbstractPayload;

class MovieUpdatePayload extends AbstractPayload
{
    public string $name;
    public ?string $synopsis;
    public ?string $genre;
    public ?string $release_date;
    public ?string $metascore;
    public bool $available;
    public ?string $cover_url;
    public ?string $trailer_url;
}
