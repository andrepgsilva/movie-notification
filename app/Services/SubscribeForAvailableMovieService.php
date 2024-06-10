<?php

namespace App\Services;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Externals\GooglePubSubClient;
use App\Repositories\MovieRepository;
use App\Exceptions\MovieNotFoundException;

class SubscribeForAvailableMovieService
{
    public function __construct(
        private MovieRepository $movieRepository,
        private GooglePubSubClient $googlePubSubClient
    ) {
        //
    }

    public function execute(
        string $slug,
        string $email
    ): void {
        try {
            $movie = $this->movieRepository->getFirstBySlug($slug);
            if (is_null($movie)) {
                throw new MovieNotFoundException();
            }

            $this->googlePubSubClient->createTopic(
                $slug
            );

            $subscriptionEndpoint = route('receive-movie-subscription-message') . "/?email=$email";
    
            $this->googlePubSubClient->createPushSubscription(
                $movie['slug'],
                str_replace('@', '%', $email),
                $subscriptionEndpoint
            );
        } catch (MovieNotFoundException $e) {
            Log::error("Movie with slug $slug not found.");

            throw $e;
        } catch (Throwable $th) {
            throw new Exception(
                'Error while subscribing for a movie.',
                500,
                $th
            );
        }
    }
}
