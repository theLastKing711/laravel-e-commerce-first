<?php

namespace App\Http\Controllers\Store;

use App\Data\Admin\Auth\LoginData;
use App\Data\Admin\Auth\LoginDataResponse;
use App\Data\Shared\LoginFailedResponse;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

class AuthController extends Controller
{
    #[OAT\Post(
        path: '/store/auth/login',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: LoginData::class),
        ),
        tags: ['storeAuth'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'User logged in successfully',
                content: new OAT\JsonContent(type: LoginDataResponse::class),
            ),
            new OAT\Response(
                response: 401,
                description: 'User name and password or incorrect',
                content: new OAT\JsonContent(type: LoginFailedResponse::class),
            ),

        ],
    )]
    public function login(Request $request, LoginData $data): mixed
    {
        Log::info(
            'accessing Store login method with name {name} and password {password}',
            [
                'name' => $request->name,
                'password' => $request->password,

            ]
        );

        $isUserAuthenticated = Auth::attempt(['name' => $data->name, 'password' => $data->password]);

        if ($isUserAuthenticated) {

            $isAStoreUser = Auth::user()->hasRole(RolesEnum::USER->value);

            if (! $isAStoreUser) {
                return response()
                    ->json(
                        new LoginFailedResponse('invalid credentials'),
                        401
                    );
            }

            return LoginDataResponse::from(Auth::user());
        }

        return response()
            ->json(
                new LoginFailedResponse('invalid credentials'),
                401
            );

    }

    #[OAT\Post(
        path: '/store/auth/logout',
        tags: ['storeAuth'],
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
