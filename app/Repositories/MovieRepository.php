<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository
{
    public function __construct(
        private Movie $model
    ) {
        //
    }
    
    public function getById(int $movieId): ?Movie
    {
        return $this->model->find($movieId);
    }

    public function getFirstBySlug(string $slug): ?Movie
    {
        return $this->model->where('slug', $slug)->first();
    }
    
    public function update(int|Movie $movie, array $movieInformation): Movie
    {
        if (! ($movie instanceof Movie)) {
            $movie = $this->model->where('id', $movie)->first();
        }

        $movie = $movie->fill($movieInformation);
        $movie->save();
        
        return $movie;
    }
}
