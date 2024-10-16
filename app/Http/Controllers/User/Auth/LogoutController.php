<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OAT;

class LogoutController extends Controller
{
    #[OAT\Post(path: '/user/auth/logout', tags: ['userAuth'])]
    #[SuccessNoContentResponse('User logged out successfully')]
    public function __invoke(Request $request)
    {
        $request->user()->tokens()->delete();

    }
}
