<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

final class LoginController extends Controller
{
    public function destroy(Request $request): JsonResponse
    {
        try {
            $userSession = $request->user();
            if ($userSession === null) {
                return response()->json([
                    'message' => 'Logged out successfully.',
                ]);
            }

            $user = User::query()
                ->where('id', $userSession->id)
                ->with('tokens')
                ->firstOrFail();

            $tokens = $user->tokens->pluck('id');
            Token::query()
                ->whereIn('id', $tokens)
                ->update([
                    'revoked' => true,
                ]);

            RefreshToken::query()
                ->whereIn('access_token_id', $tokens)
                ->update([
                    'revoked' => true,
                ]);

            return response()->json([
                'message' => 'Logged out successfully.',
            ]);
        } finally {
            $request->session()->invalidate();
            Auth::logout();
        }
    }
}
