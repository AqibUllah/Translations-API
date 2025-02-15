<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{

    #[OA\Post(
        path: "/api/auth/login",
        summary: "User Login",
        description: "Authenticate a user and return an access token.",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email","password"],
                properties: [
                    new OA\Property(property: "email",type:"string",example:"user@example.com"),
                    new OA\Property(property: "password",type:"string",example:"*****"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful login",
                content: new OA\JsonContent(
                    properties: [
                    new OA\Property(property: "token", type:"string",example: "abcsldjflsdjsd2342|sdfds")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Invalid Credentials",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type:"string",example: "Invalid credentials")
                    ]
                )
            ),
        ],

    )]
    public function login(Request $request): JsonResponse
    {
        if (!$request->wantsJson()) {
            return response()->json(['message' => 'The request must accept JSON.'], 406);
        }

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        // Generate a plain text token for user
        $token = auth()->user()->createToken('userApiToken')->plainTextToken;

        return response()->json(['token' => $token]);
    }

}
