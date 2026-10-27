<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachThue extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'KhachThue';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maKhach';

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
        'maNguoiDung',
        'hoTen',
        'soCCCD',
        'soDienThoai',
        'diaChi',
        'dangHoatDong',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dangHoatDong' => 'boolean',
    ];

    /**
     * Tài khoản người dùng liên kết (nếu có).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'maNguoiDung', 'maNguoiDung');
    }

    /**
     * Danh sách hợp đồng thuê phòng của khách.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hopDong()
    {
        return $this->hasMany(HopDong::class, 'maKhach', 'maKhach');
    }

    /**
     * Hợp đồng đang có hiệu lực hiện tại của khách thuê.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hopDongHieuLuc()
    {
        return $this->hasOne(HopDong::class, 'maKhach', 'maKhach')->where('trangThaiHD', 'HIEU_LUC');
    }
}
