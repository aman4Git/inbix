<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailService;

class EmailController extends Controller
{
    public function __construct(private EmailService $service) {}

    public function respond(Request $request)
    {
        $validated = $request->validate([
            'from_email' => 'nullable|email',
            'subject'    => 'required|string',
            'body'       => 'required|string',
        ]);

        $email = $this->service->process($validated);

        return response()->json([
            'success' => true,
            'data'    => $email
        ]);
    }
}
