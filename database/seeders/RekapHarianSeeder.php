<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekapHarian;
use App\Models\User;
use Carbon\Carbon;

class RekapHarianSeeder extends Seeder
{
    public function run()
    {
        // Ambil beberapa user (pastikan kamu sudah punya user)
        $users = User::inRandomOrder()->take(3)->get();

        foreach ($users as $user) {
            for ($i = 0; $i < 7; $i++) {
                RekapHarian::create([
                    'id_users' => $user->id,
                    'tanggal' => Carbon::today()->subDays($i),
                    'total_omzet' => rand(100000, 500000),
                    'jumlah_pelanggan' => rand(10, 50),
                    'total_pengeluaran' => rand(10000, 50000),
                    'catatan' => 'Catatan untuk tanggal ' . Carbon::today()->subDays($i)->toDateString(),
                ]);
            }
        }
    }
}
