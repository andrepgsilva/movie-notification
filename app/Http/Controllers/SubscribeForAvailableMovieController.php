<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Externals\GooglePubSubClient;
use App\Services\SubscribeForAvailableMovieService;

class SubscribeForAvailableMovieController extends Controller
{
    public function __construct(
        private GooglePubSubClient $googlePubSubClient,
        private SubscribeForAvailableMovieService $subscribeForAvailableMovieService
    ) {
        //
    }
    
    public function __invoke(Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'email' => 'required|string'
        ]);

        $this->subscribeForAvailableMovieService->execute(
            $request['slug'],
            strtolower($request['email'])
        );

        return response()->json([
            'success' => true
        ]);
    }
}
