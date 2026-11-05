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
        'soLuongNguoi',
        'ngayBatDau',
        'ngayKetThuc',
        'giaThueChot',
        'tienDatCoc',
        'tienHoanCoc',
        'trangThaiHD',
        'ghiChu',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'soLuongNguoi' => 'integer',
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
     * Thông tin khách thuê đại diện ký hợp đồng (1 người đại diện duy nhất).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function khachThue()
    {
        return $this->belongsTo(KhachThue::class, 'maKhach', 'maKhach');
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

    /**
     * Danh sách khách thuê ở cùng đang hoạt động trong hợp đồng này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function danhSachNguoiOCung()
    {
        return $this->hasMany(NguoiOCung::class, 'maHopDong', 'maHopDong')->where('dangHoatDong', 1);
    }

    /**
     * Tính tổng số người thực tế đang ở trong phòng (1 đại diện + N người ở cùng).
     *
     * @return int
     */
    public function layTongSoNguoiHienTai()
    {
        return 1 + $this->danhSachNguoiOCung()->count();
    }

    /**
     * Kiểm tra phòng trong hợp đồng còn chỗ trống không.
     *
     * @return bool
     */
    public function conChoTrong()
    {
        $sucChua = $this->phongTro ? $this->phongTro->soNguoiToiDa : 1;
        return $this->layTongSoNguoiHienTai() < $sucChua;
    }
}
