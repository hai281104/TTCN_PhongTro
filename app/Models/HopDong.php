<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HopDong extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'HopDong';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maHopDong';

    /**
     * Không sử dụng created_at và updated_at mặc định của Laravel.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Các trường được phép gán giá trị hàng loạt.
     * LƯU Ý: Cột 'phongDangThue' là cột GENERATED ALWAYS AS (IF(trangThaiHD = 'HIEU_LUC', maPhong, NULL)) STORED,
     * tuyệt đối KHÔNG đưa vào $fillable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'maPhong',
        'maKhach',
        'ngayBatDau',
        'ngayKetThuc',
        'giaThueChot',
        'tienDatCoc',
        'tienHoanCoc',
        'trangThaiHD',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ngayBatDau' => 'date',
        'ngayKetThuc' => 'date',
        'giaThueChot' => 'decimal:0',
        'tienDatCoc' => 'decimal:0',
        'tienHoanCoc' => 'decimal:0',
    ];

    /**
     * Thông tin phòng trọ được thuê.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function phongTro()
    {
        return $this->belongsTo(PhongTro::class, 'maPhong', 'maPhong');
    }

    /**
     * Thông tin khách thuê đại diện ký hợp đồng.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function khachThue()
    {
        return $this->belongsTo(KhachThue::class, 'maKhach', 'maKhach');
    }

    /**
     * Danh sách những người ở cùng trong phòng trọ.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nguoiOCung()
    {
        return $this->hasMany(NguoiOCung::class, 'maHopDong', 'maHopDong');
    }

    /**
     * Danh sách hóa đơn phát sinh của hợp đồng này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hoaDon()
    {
        return $this->hasMany(HoaDon::class, 'maHopDong', 'maHopDong');
    }
}
