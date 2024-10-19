<?php

namespace App\Data\Admin\User\Auth;

use App\Data\Admin\User\UserData;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class LoginSuccessResponseData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public UserData $user,
        #[OAT\Property()]
        public $token,
    ) {
    }
}
