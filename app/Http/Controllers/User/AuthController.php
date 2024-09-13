<?php

namespace App\Http\Controllers\User;

use App\Data\Admin\Auth\LoginDataResponse;
use App\Data\Shared\LoginFailedResponse;
use App\Data\User\Auth\LoginData;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

class AuthController extends Controller
{
    #[OAT\Post(
        path: '/user/auth/login',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: LoginData::class),
        ),
        tags: ['userAuth'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'User logged in successfully',
                content: new OAT\JsonContent(type: LoginDataResponse::class),
            ),
            new OAT\Response(
                response: 401,
                description: 'credentials are invalid',
                content: new OAT\JsonContent(type: LoginFailedResponse::class),
            ),

        ],
    )]
    public function login(Request $request, LoginData $data): mixed
    {
        Log::info(
            'accessing User login method with dial_code {dial_code} and number {number}',
            [
                'dial_code' => $request->dial_code,
                'number' => $request->number,
            ]
        );

        $user = User::where([
            'dial_code' => $request->dial_code,
            'number' => $request->number,
            'code' => $request->code,
        ])->first();

        if ($user) {

            $isUser = $user->hasRole(RolesEnum::USER->value);

            if (! $isUser) {
                return response()
                    ->json(
                        new LoginFailedResponse('invalid credentials'),
                        401
                    );
            }

            Auth::login($user);

            return LoginDataResponse::from(Auth::user());
        }

        return response()
            ->json(
                new LoginFailedResponse('invalid credentials'),
                401
            );

    }

    #[OAT\Post(
        path: '/user/auth/logout',
        tags: ['userAuth'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The User was successfully logged out',
                content: new OAT\JsonContent(type: 'boolean'),
            ),
        ],
    )]
    public function logout(Request $request): bool
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return true;
    }
}
