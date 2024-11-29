<?php

namespace App;

use App\Data\Shared\ModelwithPivotCollection;
use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use ReflectionClass;

class AddPivotDocHook implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {

        $x = $model;

        $reflection_class = new ReflectionClass($x);

        $methods = $reflection_class->getMethods();

        // $doc_comment = $reflection_class->getDocComment();

        foreach ($methods as $method) {
            $return_type =
                $method
                    ->getReturnType()
                    ?->getName();

            $belong_to_many_class = BelongsToMany::class;

            if ($return_type == $belong_to_many_class) {

                /** @var BelongsToMany $method_invocation */
                $method_invocation = $method->invoke($model);

                $pivot_model =
                    '\\'
                    .
                    $method_invocation
                        ->getPivotClass();

                $related_model =
                    '\\'
                    .
                    get_class(
                        $method_invocation
                            ->getRelated()
                    );

                if ($pivot_model) {

                }
                $z = '\\'.ModelwithPivotCollection::class;

                $property_docs =
                $z
                .'<'
                .$related_model
                .','
                .$pivot_model
                .
                '>';

                $property_name = $method->getName();

                $command
                    ->setProperty($property_name, $property_docs, true);

            }

        }

        // if (! $model instanceof Pivot) {
        //     return;
        // }

    }
}
