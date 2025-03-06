<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get manager users to assign as company owners
        $managers = User::role('manager')->get();

        // If no managers exist, we can't create companies, so return
        if ($managers->isEmpty()) {
            $this->command->info('No manager users found. Companies not created.');
            return;
        }

        // Define sample restaurant data
        $restaurants = [
            [
                'name' => 'Burger Palace',
                'description' => 'Gourmet burgers and sides made with fresh ingredients.',
                'email' => 'info@burgerpalace.com',
                'phone' => '+90 555 123 4567',
                'website' => 'www.burgerpalace.com',
                'tax_number' => '1234567890',
                'business_hours' => [
                    'monday' => ['10:00', '22:00'],
                    'tuesday' => ['10:00', '22:00'],
                    'wednesday' => ['10:00', '22:00'],
                    'thursday' => ['10:00', '22:00'],
                    'friday' => ['10:00', '23:00'],
                    'saturday' => ['11:00', '23:00'],
                    'sunday' => ['11:00', '22:00'],
                ],
                'delivery_radius' => 5.0,
                'delivery_fee' => 10.0,
                'minimum_order' => 50.0,
            ],
            [
                'name' => 'Pizza Heaven',
                'description' => 'Authentic Italian pizzas baked in a wood-fired oven.',
                'email' => 'orders@pizzaheaven.com',
                'phone' => '+90 555 765 4321',
                'website' => 'www.pizzaheaven.com',
                'tax_number' => '0987654321',
                'business_hours' => [
                    'monday' => ['11:00', '23:00'],
                    'tuesday' => ['11:00', '23:00'],
                    'wednesday' => ['11:00', '23:00'],
                    'thursday' => ['11:00', '23:00'],
                    'friday' => ['11:00', '00:00'],
                    'saturday' => ['11:00', '00:00'],
                    'sunday' => ['12:00', '22:00'],
                ],
                'delivery_radius' => 7.0,
                'delivery_fee' => 15.0,
                'minimum_order' => 70.0,
            ],
            [
                'name' => 'Sushi Express',
                'description' => 'Fresh sushi and Japanese cuisine delivered fast.',
                'email' => 'contact@sushiexpress.com',
                'phone' => '+90 555 987 6543',
                'website' => 'www.sushiexpress.com',
                'tax_number' => '5678901234',
                'business_hours' => [
                    'monday' => ['12:00', '22:00'],
                    'tuesday' => ['12:00', '22:00'],
                    'wednesday' => ['12:00', '22:00'],
                    'thursday' => ['12:00', '22:00'],
                    'friday' => ['12:00', '23:00'],
                    'saturday' => ['12:00', '23:00'],
                    'sunday' => ['13:00', '21:00'],
                ],
                'delivery_radius' => 4.0,
                'delivery_fee' => 20.0,
                'minimum_order' => 100.0,
            ],
            [
                'name' => 'Taco Fiesta',
                'description' => 'Authentic Mexican tacos and burritos with spicy sauces.',
                'email' => 'hola@tacofiesta.com',
                'phone' => '+90 555 246 8135',
                'website' => 'www.tacofiesta.com',
                'tax_number' => '1357924680',
                'business_hours' => [
                    'monday' => ['11:00', '22:00'],
                    'tuesday' => ['11:00', '22:00'],
                    'wednesday' => ['11:00', '22:00'],
                    'thursday' => ['11:00', '22:00'],
                    'friday' => ['11:00', '23:30'],
                    'saturday' => ['11:00', '23:30'],
                    'sunday' => ['12:00', '21:00'],
                ],
                'delivery_radius' => 6.0,
                'delivery_fee' => 12.0,
                'minimum_order' => 60.0,
            ],
            [
                'name' => 'Green Garden',
                'description' => 'Healthy salads, bowls, and vegetarian options for the health-conscious.',
                'email' => 'eat@greengarden.com',
                'phone' => '+90 555 369 8520',
                'website' => 'www.greengarden.com',
                'tax_number' => '2468013579',
                'business_hours' => [
                    'monday' => ['09:00', '20:00'],
                    'tuesday' => ['09:00', '20:00'],
                    'wednesday' => ['09:00', '20:00'],
                    'thursday' => ['09:00', '20:00'],
                    'friday' => ['09:00', '20:00'],
                    'saturday' => ['10:00', '18:00'],
                    'sunday' => ['10:00', '18:00'],
                ],
                'delivery_radius' => 8.0,
                'delivery_fee' => 5.0,
                'minimum_order' => 40.0,
            ],
        ];

        // Create a company for each restaurant
        foreach ($restaurants as $index => $restaurantData) {
            // Assign a manager as the owner (cycling through available managers)
            $owner = $managers[$index % count($managers)];

            // Create the company first
            $company = Company::firstOrCreate(
                ['name' => $restaurantData['name']],
                [
                    'description' => $restaurantData['description'],
                    'logo_path' => null, // Would typically store a path to a logo image
                    'email' => $restaurantData['email'],
                    'phone' => $restaurantData['phone'],
                    'website' => $restaurantData['website'],
                    'tax_number' => $restaurantData['tax_number'],
                    'owner_uuid' => $owner->uuid,
                    'address_uuid' => null, // Will be updated after address creation
                    'business_hours' => $restaurantData['business_hours'],
                    'delivery_radius' => $restaurantData['delivery_radius'],
                    'delivery_fee' => $restaurantData['delivery_fee'],
                    'minimum_order' => $restaurantData['minimum_order'],
                    'is_active' => true,
                    'is_featured' => $index < 2, // First two restaurants will be featured
                ]
            );

            // Now create the address with the company_uuid already set
            $address = $this->createCompanyAddress($company->uuid);

            // Update the company with the main address
            $company->update(['address_uuid' => $address->uuid]);
        }
    }

    /**
     * Create a random address for a company
     */
    private function createCompanyAddress(string $companyUuid): Address
    {
        $cities = ['Istanbul', 'Ankara', 'Izmir', 'Bursa', 'Antalya'];
        $districts = ['Kadikoy', 'Besiktas', 'Sisli', 'Uskudar', 'Beyoglu'];
        $streets = ['Main Street', 'Park Avenue', 'Ocean Boulevard', 'Market Street', 'Central Avenue'];

        return Address::create([
            'company_uuid' => $companyUuid, // Set company_uuid directly
            'title' => 'Business Address',
            'address_line1' => rand(1, 100) . ' ' . $streets[array_rand($streets)],
            'address_line2' => 'Floor ' . rand(1, 10) . ', No: ' . rand(1, 100),
            'city' => $cities[array_rand($cities)],
            'district' => $districts[array_rand($districts)],
            'country' => 'Turkey',
            'postal_code' => rand(10000, 99999),
            'is_default' => true,
        ]);
    }
}
