<?php

namespace App\Services;

use App\Payloads\MovieUpdatePayload;
use App\Repositories\MovieRepository;
use App\Services\PublishMessageWhenMovieAvailableService;

class MovieService
{
    public function __construct(
        private MovieRepository $movieRepository,
        private PublishMessageWhenMovieAvailableService $publishMessageWhenMovieAvailableService,
    ) {
        //
    }

    public function updateMovie(
        int $movieId,
        MovieUpdatePayload $movieUpdatePayload
    ): void {
        $movieInformation = $movieUpdatePayload->toArray();
        $movieAvailable = $movieInformation['available'] ?? null;

        $movie = $this->movieRepository->getById($movieId);
        $movieAvailableColumnOnDb = $movie->available;
        
        $movie = $this->movieRepository->update($movie, $movieInformation);
        
        if (! $movieAvailableColumnOnDb && $movieAvailable) {
            $this->publishMessageWhenMovieAvailableService->execute(
                $movie->name,
                json_encode(['data' => $movie->toArray()])
            );
        }
    }
}
