<?php
namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $types = RoomType::pluck('id', 'name');

        $rooms = [
            // Étage 1 — Simples
            ['number' => '101', 'room_type_id' => $types['Chambre Simple'],  'floor' => 1, 'status' => 'available'],
            ['number' => '102', 'room_type_id' => $types['Chambre Simple'],  'floor' => 1, 'status' => 'available'],
            ['number' => '103', 'room_type_id' => $types['Chambre Double'],  'floor' => 1, 'status' => 'available'],
            ['number' => '104', 'room_type_id' => $types['Chambre Double'],  'floor' => 1, 'status' => 'available'],
            ['number' => '105', 'room_type_id' => $types['Chambre Twin'],   'floor' => 1, 'status' => 'available'],
            // Étage 2
            ['number' => '201', 'room_type_id' => $types['Chambre Simple'],  'floor' => 2, 'status' => 'available'],
            ['number' => '202', 'room_type_id' => $types['Chambre Double'],  'floor' => 2, 'status' => 'available'],
            ['number' => '203', 'room_type_id' => $types['Chambre Double'],  'floor' => 2, 'status' => 'available'],
            ['number' => '204', 'room_type_id' => $types['Chambre Twin'],   'floor' => 2, 'status' => 'maintenance'],
            ['number' => '205', 'room_type_id' => $types['Suite Junior'],   'floor' => 2, 'status' => 'available'],
            // Étage 3 — Suites
            ['number' => '301', 'room_type_id' => $types['Suite Junior'],        'floor' => 3, 'status' => 'available'],
            ['number' => '302', 'room_type_id' => $types['Suite Junior'],        'floor' => 3, 'status' => 'available'],
            ['number' => '303', 'room_type_id' => $types['Suite Présidentielle'],'floor' => 3, 'status' => 'available'],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['number' => $room['number']], $room);
        }
    }
}
