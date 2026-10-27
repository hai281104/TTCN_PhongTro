<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongTro extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'PhongTro';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maPhong';

    /**
     * Không sử dụng created_at và updated_at mặc định của Laravel.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Các trường được phép gán giá trị hàng loạt.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'soPhong',
        'donGiaMacDinh',
        'trangThaiPhong',
        'ghiChu',
        'dangHoatDong',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'donGiaMacDinh' => 'decimal:0',
        'dangHoatDong' => 'boolean',
    ];

    /**
     * Danh sách hợp đồng thuê của phòng trọ.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hopDong()
    {
        return $this->hasMany(HopDong::class, 'maPhong', 'maPhong');
    }

    /**
     * Hợp đồng đang có hiệu lực hiện tại của phòng trọ.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hopDongHieuLuc()
    {
        return $this->hasOne(HopDong::class, 'maPhong', 'maPhong')->where('trangThaiHD', 'HIEU_LUC');
    }

    /**
     * Danh sách chỉ số điện nước ghi nhận hàng tháng của phòng.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chiSoDienNuoc()
    {
        return $this->hasMany(ChiSoDienNuoc::class, 'maPhong', 'maPhong');
    }
}
