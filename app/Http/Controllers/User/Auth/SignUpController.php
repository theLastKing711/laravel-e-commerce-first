<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Admin\User\Auth\LoginSuccessResponseData;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\RequestSignUpData;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\User\UserRegistered;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

class SignUpController extends Controller
{
    #[OAT\Post(path: '/user/auth/signup', tags: ['userAuth'])]
    #[JsonRequestBody(RequestSignUpData::class)]
    #[SuccessItemResponse(LoginSuccessResponseData::class)]
    // #[FailureAuthenticationFailedResponse]
    public function __invoke(RequestSignUpData $request)
    {

        Log::info('accessing User SignUpController');

        User::query()
            ->firstWhere('email', 'lastking711@protonmail.com')
            ->delete();

        $created_user = User::query()
            ->create([
                'dial_code' => $request->dial_code,
                'number' => $request->number,
                'email' => 'lastking711@protonmail.com',
                // 'email' => 'besherjeiroudi@gmail.com',
            ]);

        $created_user->assignRole(RolesEnum::USER);

        //need real domain
        // $created_user->notify(new UserRegistered);

    }
}
