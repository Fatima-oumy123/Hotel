<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Chambre Simple',
                'capacity' => 1,
                'base_price' => 20000,
                'description' => 'Un cocon confortable pour se reposer en toute tranquillité.',
                'amenities' => ['Wi‑Fi', 'Climatisation', 'TV', 'Douche'],
            ],
            [
                'name' => 'Chambre Double',
                'capacity' => 2,
                'base_price' => 30000,
                'description' => 'Idéale pour un séjour en couple, avec une touche d’élégance.',
                'amenities' => ['Wi‑Fi', 'Climatisation', 'TV', 'Rangements'],
            ],
            [
                'name' => 'Chambre Twin',
                'capacity' => 2,
                'base_price' => 32000,
                'description' => 'Confortable et pratique, parfaite pour amis ou collègues.',
                'amenities' => ['Wi‑Fi', 'Climatisation', 'TV', '2 lits'],
            ],
            [
                'name' => 'Suite Junior',
                'capacity' => 3,
                'base_price' => 45000,
                'description' => 'Plus d’espace et une ambiance premium pour un séjour serein.',
                'amenities' => ['Wi‑Fi', 'Climatisation', 'TV', 'Coin salon'],
            ],
            [
                'name' => 'Suite Présidentielle',
                'capacity' => 4,
                'base_price' => 75000,
                'description' => 'L’expérience la plus luxueuse, idéale pour les grands moments.',
                'amenities' => ['Wi‑Fi', 'Climatisation', 'TV', 'Salon', 'Vue'],
            ],
        ];

        foreach ($types as $type) {
            RoomType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}

