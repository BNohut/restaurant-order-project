<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies
        $companies = Company::all();

        if ($companies->isEmpty()) {
            $this->command->info('No companies found. Products not created.');
            return;
        }

        foreach ($companies as $company) {
            // Create products based on the company type/name
            $products = $this->getProductsForCompany($company->name);

            foreach ($products as $index => $productData) {
                Product::firstOrCreate(
                    [
                        'name' => $productData['name'],
                        'company_uuid' => $company->uuid
                    ],
                    [
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'image_path' => null, // Would typically store a path to a product image
                        'category' => $productData['category'],
                        'preparation_time' => $productData['preparation_time'],
                        'is_available' => true,
                        'is_featured' => $index < 2, // First two products per restaurant will be featured
                    ]
                );
            }
        }
    }

    /**
     * Get products for a specific company based on its name/type
     */
    private function getProductsForCompany(string $companyName): array
    {
        // Define products based on company type
        $productsByCompanyType = [
            'Burger Palace' => [
                [
                    'name' => 'Classic Cheeseburger',
                    'description' => 'Juicy beef patty with cheddar cheese, lettuce, tomato, and special sauce.',
                    'price' => 79.90,
                    'category' => 'Burgers',
                    'preparation_time' => 15,
                ],
                [
                    'name' => 'Double Bacon Burger',
                    'description' => 'Two beef patties with crispy bacon, cheddar cheese, and BBQ sauce.',
                    'price' => 99.90,
                    'category' => 'Burgers',
                    'preparation_time' => 20,
                ],
                [
                    'name' => 'Vegetarian Burger',
                    'description' => 'Plant-based patty with fresh vegetables and vegan mayo.',
                    'price' => 89.90,
                    'category' => 'Vegetarian',
                    'preparation_time' => 15,
                ],
                [
                    'name' => 'French Fries',
                    'description' => 'Crispy golden fries seasoned with sea salt.',
                    'price' => 29.90,
                    'category' => 'Sides',
                    'preparation_time' => 10,
                ],
                [
                    'name' => 'Onion Rings',
                    'description' => 'Crispy battered onion rings served with dipping sauce.',
                    'price' => 39.90,
                    'category' => 'Sides',
                    'preparation_time' => 12,
                ],
            ],
            'Pizza Heaven' => [
                [
                    'name' => 'Margherita Pizza',
                    'description' => 'Classic pizza with tomato sauce, mozzarella, and fresh basil.',
                    'price' => 89.90,
                    'category' => 'Pizzas',
                    'preparation_time' => 20,
                ],
                [
                    'name' => 'Pepperoni Pizza',
                    'description' => 'Pizza with tomato sauce, mozzarella, and spicy pepperoni.',
                    'price' => 99.90,
                    'category' => 'Pizzas',
                    'preparation_time' => 20,
                ],
                [
                    'name' => 'Vegetarian Pizza',
                    'description' => 'Pizza with bell peppers, mushrooms, onions, olives, and cheese.',
                    'price' => 94.90,
                    'category' => 'Vegetarian',
                    'preparation_time' => 20,
                ],
                [
                    'name' => 'Four Cheese Pizza',
                    'description' => 'Pizza with mozzarella, cheddar, parmesan, and blue cheese.',
                    'price' => 109.90,
                    'category' => 'Pizzas',
                    'preparation_time' => 20,
                ],
                [
                    'name' => 'Garlic Bread',
                    'description' => 'Freshly baked bread with garlic butter and herbs.',
                    'price' => 39.90,
                    'category' => 'Sides',
                    'preparation_time' => 10,
                ],
            ],
            'Sushi Express' => [
                [
                    'name' => 'Salmon Nigiri (2 pcs)',
                    'description' => 'Fresh salmon slices over pressed vinegared rice.',
                    'price' => 59.90,
                    'category' => 'Nigiri',
                    'preparation_time' => 10,
                ],
                [
                    'name' => 'California Roll (8 pcs)',
                    'description' => 'Crab, avocado, and cucumber rolled in rice and seaweed.',
                    'price' => 79.90,
                    'category' => 'Rolls',
                    'preparation_time' => 15,
                ],
                [
                    'name' => 'Spicy Tuna Roll (8 pcs)',
                    'description' => 'Spicy tuna and cucumber rolled in rice and seaweed.',
                    'price' => 89.90,
                    'category' => 'Rolls',
                    'preparation_time' => 15,
                ],
                [
                    'name' => 'Sashimi Plate (12 pcs)',
                    'description' => 'Assorted fresh raw fish slices: salmon, tuna, and yellowtail.',
                    'price' => 149.90,
                    'category' => 'Sashimi',
                    'preparation_time' => 10,
                ],
                [
                    'name' => 'Miso Soup',
                    'description' => 'Traditional Japanese soup with tofu, seaweed, and green onions.',
                    'price' => 29.90,
                    'category' => 'Soups',
                    'preparation_time' => 5,
                ],
            ],
            'Taco Fiesta' => [
                [
                    'name' => 'Beef Tacos (3 pcs)',
                    'description' => 'Corn tortillas with seasoned beef, lettuce, cheese, and salsa.',
                    'price' => 69.90,
                    'category' => 'Tacos',
                    'preparation_time' => 15,
                ],
                [
                    'name' => 'Chicken Burrito',
                    'description' => 'Large flour tortilla filled with grilled chicken, rice, beans, and cheese.',
                    'price' => 89.90,
                    'category' => 'Burritos',
                    'preparation_time' => 20,
                ],
                [
                    'name' => 'Vegetarian Quesadilla',
                    'description' => 'Grilled flour tortilla filled with cheese, bell peppers, and onions.',
                    'price' => 69.90,
                    'category' => 'Vegetarian',
                    'preparation_time' => 15,
                ],
                [
                    'name' => 'Guacamole & Chips',
                    'description' => 'Fresh guacamole served with crispy tortilla chips.',
                    'price' => 49.90,
                    'category' => 'Sides',
                    'preparation_time' => 10,
                ],
                [
                    'name' => 'Churros',
                    'description' => 'Fried dough pastry dusted with cinnamon sugar, served with chocolate sauce.',
                    'price' => 39.90,
                    'category' => 'Desserts',
                    'preparation_time' => 10,
                ],
            ],
            'Green Garden' => [
                [
                    'name' => 'Caesar Salad',
                    'description' => 'Romaine lettuce with Caesar dressing, croutons, and parmesan.',
                    'price' => 59.90,
                    'category' => 'Salads',
                    'preparation_time' => 10,
                ],
                [
                    'name' => 'Quinoa Buddha Bowl',
                    'description' => 'Quinoa with roasted vegetables, avocado, and tahini dressing.',
                    'price' => 79.90,
                    'category' => 'Bowls',
                    'preparation_time' => 15,
                ],
                [
                    'name' => 'Avocado Toast',
                    'description' => 'Whole grain toast with smashed avocado, cherry tomatoes, and microgreens.',
                    'price' => 49.90,
                    'category' => 'Breakfast',
                    'preparation_time' => 10,
                ],
                [
                    'name' => 'Fresh Fruit Smoothie',
                    'description' => 'Blend of seasonal fruits, yogurt, and honey.',
                    'price' => 39.90,
                    'category' => 'Drinks',
                    'preparation_time' => 5,
                ],
                [
                    'name' => 'Vegan Chocolate Cake',
                    'description' => 'Rich, moist chocolate cake made with plant-based ingredients.',
                    'price' => 49.90,
                    'category' => 'Desserts',
                    'preparation_time' => 5,
                ],
            ],
        ];

        // Return products for this specific company, or empty array if not found
        return $productsByCompanyType[$companyName] ?? [];
    }
}
