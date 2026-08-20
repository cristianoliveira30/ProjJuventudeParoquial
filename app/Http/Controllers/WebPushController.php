<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebPushController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'contentEncoding' => 'nullable|string', // se você mandar
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $user->updatePushSubscription(
            $data['endpoint'],
            $data['keys']['p256dh'],
            $data['keys']['auth'],
            $data['contentEncoding'] ?? null
        );

        return response()->json(['ok' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
        ]);

        $request->user()->deletePushSubscription($data['endpoint']);

        return response()->json(['ok' => true]);
    }
}
