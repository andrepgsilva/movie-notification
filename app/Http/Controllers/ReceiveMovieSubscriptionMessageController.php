<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\NotifyUserWhenMovieAvailableService;

class ReceiveMovieSubscriptionMessageController extends Controller
{
    public function __construct(
        private NotifyUserWhenMovieAvailableService $notifyUserWhenMovieAvailableService
    ) {
        //
    }
    
    public function __invoke(Request $request)
    {
        $request->validate([
            'message.data' => 'required'
        ]);

        $this->notifyUserWhenMovieAvailableService->send(
            $request->all(),
            $request->get('email')
        );

        return response()->json([
            'success' => true
        ]);
    }
}
