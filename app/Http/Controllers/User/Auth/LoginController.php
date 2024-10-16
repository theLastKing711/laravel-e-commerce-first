<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Admin\User\Auth\LoginSuccessResponseData;
use App\Data\Admin\User\UserData;
use App\Data\Shared\LoginFailedResponse;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\FailureAuthenticationFailedResponse;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\LoginData;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

class LoginController extends Controller
{
    #[OAT\Post(path: '/user/auth/login', tags: ['userAuth'])]
    #[JsonRequestBody(LoginData::class)]
    #[SuccessItemResponse(LoginSuccessResponseData::class)]
    #[FailureAuthenticationFailedResponse]
    public function __invoke(Request $request)
    {
        Log::info(
            'accessing User login method with dial_code {dial_code} and number {number}',
            [
                'dial_code' => $request->dial_code,
                'number' => $request->number,
            ]
        );

        $user = User::query()
            ->where([
                'dial_code' => $request->dial_code,
                'number' => $request->number,
                'code' => $request->code,
            ])
            ->first();

        Log::info('user {user}', ['user' => $user->number]);

        if ($user) {

            Log::info('user {user}', ['user' => $request->user()]);

            $isUser = $user->hasRole(RolesEnum::USER->value);

            if (! $isUser) {
                Log::info('User failed to log in');

                return response()
                    ->json(
                        new LoginFailedResponse('invalid credentials'),
                        401
                    );
            }

            Log::info('User Logged in successfully');

            $token = $user->createToken('auth_token');

            return LoginSuccessResponseData::from([
                'user' => UserData::from($user),
                'token' => $token->plainTextToken,
            ]);
        }

        Log::info('User failed to log in');

        return response()
            ->json(
                new LoginFailedResponse('invalid credentials'),
                401
            );

    }
}
