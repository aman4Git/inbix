<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\EmailService;

class EmailController extends Controller
{
    public function __construct(private EmailService $service) {}

    public function respond(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from_email' => 'required|email',
            'subject'    => 'required|string',
            'body'       => 'required|string',
        ]);

        try {
            $email = $this->service->process($validated);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process email',
                'data'    => null,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email processed successfully',
            'data'    => $email,
        ]);
    }
}
