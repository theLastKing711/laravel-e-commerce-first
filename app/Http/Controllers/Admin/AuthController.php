<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Auth\LoginData;
use App\Data\Admin\Auth\LoginDataResponse;
use App\Data\Shared\LoginFailedResponse;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class AuthController extends Controller
{
    /**
     * Get Token
     */
    #[
        OAT\Get(
            path: '/sanctum/csrf-cookie',
            tags: ['sanctum'],
            //        parameters: [
            ////            new OAT\HeaderParameter(
            ////                name: 'X-CSRF-TOKEN',
            ////            ),
            //            new OAT\HeaderParameter(
            //                name: 'Origin',
            //                schema: new OAT\Schema(
            //                    type: 'string',
            //                    default: 'http://127.0.0.1:8000/'
            //                )
            //            ),
            //            new OAT\HeaderParameter(
            //                name: 'Accept',
            //                schema: new OAT\Schema(
            //                    type: 'string',
            //                    default: 'application/json',
            //                )
            //            ),
            //        ],
            responses: [
                new OAT\Response(
                    response: 200,
                    description: 'csrf cookie set',
                    headers: [
                        new OAT\Header(
                            header: 'Set-Cookie',
                            schema: new OAT\Schema(
                                type: 'string',
                            )
                        ),
                    ]
                ),
            ],
        ),
        //        OAT\SecurityScheme(
        //            securityScheme: 'basicAuth',
        //            type: 'apiKey',
        //        )
    ]
    public function getToken()
    {
        return ['allah'];
    }

    #[OAT\Tag('auth')]
    #[OAT\Post(
        path: '/admin/auth/login',
        tags: ['auth'],
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
    #[JsonRequestBody(LoginData::class)]
    public function login(Request $request, LoginData $data): mixed
    {
        if (Auth::attempt(['name' => $data->name, 'password' => $data->password])) {

            return LoginDataResponse::from(Auth::user());
        }

        return response()
            ->json(
                new LoginFailedResponse('invalid credentials'),
                401
            );

    }

    #[OAT\Post(
        path: '/admin/auth/logout',
        tags: ['auth'],
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
