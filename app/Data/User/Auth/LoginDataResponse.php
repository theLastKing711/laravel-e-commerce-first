<?php

namespace App\Data\User\Auth;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'userLoginResponse')]
class LoginDataResponse extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public string $email,
        #[OAT\Property()]
        public string $created_at,
    ) {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            created_at: $user->created_at
        );
    }

}
