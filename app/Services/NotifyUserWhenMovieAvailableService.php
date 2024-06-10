<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;
use App\Notifications\MovieAvailable;
use App\Repositories\MovieRepository;
use App\Services\PublishMessageWhenMovieAvailableService;

class NotifyUserWhenMovieAvailableService
{
    public function __construct(
        private MovieRepository $movieRepository,
        private UserRepository $userRepository,
        private PublishMessageWhenMovieAvailableService $PublishMessageWhenMovieAvailableService,
    ) {
        //
    }

    public function send(
        array $pubSubMessage,
        string $email,
    ): void {
        $encryptedMessage = $pubSubMessage['message']['data'];
        $movieInformation = json_decode(base64_decode($encryptedMessage), true);
        
        Log::info('NotifyUserWhenMovieAvailableService', [
            'pubSubMessage' => $pubSubMessage,
            'encryptedMessage' => $encryptedMessage,
            'movieInformation' => $movieInformation
        ]);

        $movie = $this->movieRepository->getFirstBySlug($movieInformation['slug']);
        $user = $this->userRepository->getFirstByEmail($email);
        $user->notify(new MovieAvailable($movie));
    }
}
