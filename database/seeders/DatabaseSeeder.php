<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Enums\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'testU',
            'email' => 'testU@gmail.com',
            'role' => Role::User->value,
            'password' => 'testUtestU',
        ]);

        User::factory()->create([
            'name' => 'testA',
            'email' => 'testA@gmail.com',
            'role' => Role::Admin->value,
            'password' => 'testA',
        ]);

        User::factory()->create([
            'name' => 'testK',
            'email' => 'testK@gmail.com',
            'role' => Role::Karyawan->value,
            'password' => 'testK',
        ]);

        User::factory()->create([
            'name' => 'User2',
            'email' => 'User2@gmail.com',
            'role' => Role::User->value,
            'password' => 'User2',
        ]);

        $produk = [
            // Sarapan Pagi
            [
                'nama' => 'Ayam Kremes Sambal Cobek',
                'deskripsi' => 'PAKET AYAM KREMES + SAMBAL COBEK',
                'harga' => 23000,
                'image' => 'produk/kremes1.jpg',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Kremes Sambal Geprek',
                'deskripsi' => 'PAKET AYAM KREMES + SAMBAL GEPREK',
                'harga' => 22000,
                'image' => 'produk/kremes2.jpg',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Crispy Keju Paha Bawah',
                'deskripsi' => 'AYAM KEJU PAHA BAWAH NASI',
                'harga' => 16000,
                'image' => 'produk/keju1.jpg',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Crispy Original Sayap',
                'deskripsi' => 'AYAM KEJU SAYAP NASI',
                'harga' => 13000,
                'image' => 'produk/keju2.jpg',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Crispy Original Dada',
                'deskripsi' => 'AYAM DADA NASI + SAUS SAMBAL',
                'harga' => 15000,
                'image' => 'produk/chicken.png',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Crispy Original Paha Atas',
                'deskripsi' => 'AYAM PAHA ATAS NASI + SAUS SAMBAL',
                'harga' => 15000,
                'image' => 'produk/chicken.png',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Geprek Paha Bawah',
                'deskripsi' => 'AYAM GEPREK (TEMPE/TAHU/ PAHA BAWAH NASI + PILIHAN SALAD/SAYUR',
                'harga' => 18500,
                'image' => 'produk/geprek1.jpg',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Geprek Sayap',
                'deskripsi' => 'AYAM GEPREK (TEMPE/TAHU/ SAYAP NASI + PILIHAN SALAD/SAYUR',
                'harga' => 34000,
                'image' => 'produk/geprek2.jpg',
                'kategori' => 'sarapan',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ayam Crispy
            [
                'nama' => 'Ayam Crispy Original Paha Bawah',
                'deskripsi' => 'AYAM PAHA BAWAH NASI + SAUS SAMBAL',
                'harga' => 13000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Crispy Original Sayap',
                'deskripsi' => 'AYAM PAHA SAYAP NASI + SAUS SAMBAL',
                'harga' => 13000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Crispy Original Dada',
                'deskripsi' => 'AYAM DADA NASI + SAUS SAMBAL',
                'harga' => 15000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ayam Crispy Original Paha Atas',
                'deskripsi' => 'AYAM PAHA ATAS NASI + SAUS SAMBAL',
                'harga' => 15000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Paket Ayam Crispy 1',
                'deskripsi' => '1 potong ayam crispy + nasi + minum, paket lengkap.',
                'harga' => 40000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Paket Ayam Crispy 2',
                'deskripsi' => '2 potong ayam crispy + nasi + minum, untuk yang lapar.',
                'harga' => 55000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Paket Keluarga',
                'deskripsi' => '4 potong ayam crispy + nasi + minum, cocok untuk keluarga.',
                'harga' => 100000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Paket Rame-Rame',
                'deskripsi' => 'Berbagai varian ayam crispy + kentang + minum, untuk dinikmati bersama.',
                'harga' => 120000,
                'image' => 'produk/chicken.png',
                'kategori' => 'ayam_crispy',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert produk data
        DB::table('produk')->insert($produk);

        $this->command->info('Produk data seeded successfully!');
    }
}
