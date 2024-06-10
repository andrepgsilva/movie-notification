<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Externals\GooglePubSubClient;

class PublishMessageWhenMovieAvailableService
{
    public function __construct(
        private GooglePubSubClient $googlePubSubClient
    ) {
        //
    }

    public function execute(
        string $topicName,
        string $message
    ): void {
        $topicName = Str::slug($topicName);

        $topicExists = $this->googlePubSubClient->checkIfTopicExists(
            $topicName
        );

        if (! $topicExists) {
            return;
        }
        
        $this->googlePubSubClient->publishMessage(
            $topicExists['name'],
            $message
        );
    }
}
