<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiPhong extends Model
{
    use HasFactory;

    protected $table = 'LoaiPhong';

    protected $primaryKey = 'maLoaiPhong';

    public $timestamps = false;

    protected $fillable = [
        'tenLoaiPhong',
        'soNguoiToiDa',
        'dienTich',
        'donGiaGoiY',
        'moTa',
        'dangHoatDong',
    ];

    protected $casts = [
        'soNguoiToiDa' => 'integer',
        'dienTich' => 'decimal:2',
        'donGiaGoiY' => 'decimal:0',
        'dangHoatDong' => 'boolean',
    ];

    // Danh sách các phòng trọ thuộc thể loại này
    public function phongTro()
    {
        return $this->hasMany(PhongTro::class, 'maLoaiPhong', 'maLoaiPhong');
    }
}

