<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => ucwords($this->faker->words(3, true)),
            'price' => $this->faker->numberBetween(15000, 500000),
            'stock' => $this->faker->numberBetween(5, 100),
            'description' => $this->faker->paragraph(),
            // Mensimulasikan array gambar sesuai dengan casting di model
            'image' => [
                'products/' . $this->faker->lexify('??????') . '.jpg'
            ], 
            'is_active' => $this->faker->boolean(90),
        ];
    }
}