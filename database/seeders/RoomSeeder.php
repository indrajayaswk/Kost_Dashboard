<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'room_number' => 'A1',
                'room_type' => 'Lantai Atas',
                'room_status' => 'available',
                'room_price' => 2000000,
            ],
            [
                'room_number' => 'A2',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],
            [
                'room_number' => 'A3',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],
            [
                'room_number' => 'A4',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],
            [
                'room_number' => 'A5',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],
            [
                'room_number' => 'A6',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],
            [
                'room_number' => 'A7',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],
            [
                'room_number' => 'A8',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],
            [
                'room_number' => 'A9',
                'room_type' => 'Lantai Atas',
                'room_status' => 'occupied',
                'room_price' => 3000000,
            ],



            [
                'room_number' => 'B1',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B2',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B3',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B4',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B5',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B6',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B7',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B8',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'B9',
                'room_type' => 'Lantai Bawah',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],


            [
                'room_number' => 'S1',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'S2',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'S3',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'S4',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'S5',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'S6',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'S7',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
            [
                'room_number' => 'S8',
                'room_type' => 'Kost Selatan',
                'room_status' => 'available',
                'room_price' => 5000000,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}