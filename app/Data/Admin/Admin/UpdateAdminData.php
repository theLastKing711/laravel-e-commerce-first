<?php

namespace App\Data\Admin\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema()]
class UpdateAdminData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string')]
        public string $name,
        #[OAT\Property(type: 'string')]
        public ?string $password,
    ) {
    }

    public static function rules(Request $request, ValidationContext $context): array
    {

        $user_id = $request->route('id');

        $user = User::query()
            ->find( $user_id);

        $excluded_id = -1;

        if ($user->name == $context->fullPayload['name']) {
            $excluded_id = $user->id;
        }

        return [
            'name' => 'required|unique:users,name,'.$excluded_id,
        ];
    }
}
