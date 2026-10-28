<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    public function run()
    {
        NguoiDung::updateOrCreate(
            ['email' => 'chutro@example.com'],
            [
                'matKhau' => Hash::make('123456'),
                'vaiTro' => 'CHU_TRO',
                'dangHoatDong' => 1,
            ]
        );

        NguoiDung::updateOrCreate(
            ['email' => 'nguyenvana@example.com'],
            [
                'matKhau' => Hash::make('123456'),
                'vaiTro' => 'KHACH_THUE',
                'dangHoatDong' => 1,
            ]
        );

        NguoiDung::updateOrCreate(
            ['email' => 'taikhoankhoa@example.com'],
            [
                'matKhau' => Hash::make('123456'),
                'vaiTro' => 'KHACH_THUE',
                'dangHoatDong' => 0,
            ]
        );
    }
}
