<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    public array $variants = [
        'اللون' => [
            'أصفر',
            'أحمر',
            'أخضر',
        ],
        'الحجم' => [
            'صغير',
            'وسط',
            'كبير',
        ],

    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'name' => $this->faker()->randomElements($this->variants),
        ];
    }
}
