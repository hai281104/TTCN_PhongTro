<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'DichVu';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maDichVu';

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
        'tenDichVu',
        'loaiDichVu',
        'donGia',
        'donViTinh',
        'cachTinh',
        'dangApDung',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'donGia' => 'decimal:0',
        'dangApDung' => 'boolean',
    ];

    /**
     * Danh sách các dòng chi tiết hóa đơn sử dụng dịch vụ này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chiTietHoaDon()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'maDichVu', 'maDichVu');
    }
}
