<?php

namespace App\Externals;

use Google\Cloud\PubSub\Topic;
use Illuminate\Support\Facades\Log;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Subscription;
use Google\Cloud\PubSub\MessageBuilder;
use Google\Cloud\Core\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GooglePubSubClient
{
    public function __construct(
        private PubSubClient $pubSubClient,
        private MessageBuilder $messageBuilder
    ) {
        //
    }

    /**
     * Creates a Pub/Sub topic.
     *
     * @param string $topicName The Pub/Sub topic name.
     */
    public function createTopic(string $topicName): ?array
    {
        $topic = $this->checkIfTopicExists($topicName);

        if (! is_null($topic))  {
            return $topic;
        }

        $topic = $this->pubSubClient->createTopic($topicName);

        Log::info(
            sprintf('Topic created: %s', $topic->name()),
            $topic->info()
        );

        return $topic->info();
    }

    /**
     * Publishes a message for a Pub/Sub topic.
     *
     * @param string $topicName  The Pub/Sub topic name.
     * @param string $message  The message to publish.
     */
    public function publishMessage(string $topicName, string $message): void
    {
        $topic = $this->pubSubClient->topic($topicName);
        
        $topic->publish(
            $this->messageBuilder->setData($message)->build()
        );

        Log::info(
            'Message published',
            ['topicName' => $topicName, 'message' => $message]
        );
    }

    /**
     * Creates a Pub/Sub push subscription.
     *
     * @param string $topicName  The Pub/Sub topic name.
     * @param string $subscriptionName  The Pub/Sub subscription name.
     */
    function createPushSubscription(
        string $topicName,
        string $subscriptionName,
        string $endpoint
    ): ?array {
        $topic = $this->pubSubClient->topic($topicName);

        $this->createTopic($topicName);
        
        $subscription = $this->checkIfSubscriptionExists($subscriptionName);
        if (! is_null($subscription)) {
            return $subscription;
        }

        $subscription = $topic->subscription($subscriptionName);
        $subscriptionCreated = $subscription->create([
            'pushConfig' => ['pushEndpoint' => $endpoint],
            'deadLetterPolicy' => [
                'deadLetterTopic' => config('gcp.google_cloud_main_dead_letter_topic'),
                'maxDeliveryAttempts' => 5
            ],
        ]);

        Log::info(
            sprintf('Subscription created: %s', $subscription->name()),
            ['topicName' => $topicName, 'message' => $subscriptionName]
        );

        return $subscriptionCreated;
    }

    public function checkIfTopicExists(string $topicName): ?array
    {
        $topicName = strtolower($topicName);
        $topicPrefix = config('gcp.google_cloud_topic_prefix');
        $topicName = $topicPrefix . $topicName;
        $topic = $this->pubSubClient->topic($topicName);

        try {
            return $topic->info();
        } catch (NotFoundException $e) {
            return null;
        }
    }

    public function checkIfSubscriptionExists(string $subscriptionName): ?array
    {
        $subscriptionName = strtolower($subscriptionName);
        $subscriptionPrefix = config('gcp.google_cloud_subscription_prefix');
        $subscriptionName = $subscriptionPrefix . $subscriptionName;

        $subscription = $this->pubSubClient->subscription(
            $subscriptionName
        );

        if ($subscription->exists()) {
            return $subscription->info();
        }

        return null;
    }
}
