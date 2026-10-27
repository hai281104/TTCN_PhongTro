<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'ChiTietHoaDon';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maChiTiet';

    /**
     * Không sử dụng created_at và updated_at mặc định của Laravel.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Các trường được phép gán giá trị hàng loạt.
     * LƯU Ý: Cột 'thanhTien' là cột GENERATED ALWAYS AS (ROUND(soLuong * donGia, 0)) STORED,
     * tuyệt đối KHÔNG đưa vào $fillable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'maHoaDon',
        'maDichVu',
        'noiDung',
        'soLuong',
        'donGia',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'soLuong' => 'decimal:2',
        'donGia' => 'decimal:0',
        'thanhTien' => 'decimal:0',
    ];

    /**
     * Hóa đơn chứa khoản mục này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'maHoaDon', 'maHoaDon');
    }

    /**
     * Dịch vụ tương ứng (NULL nếu là dòng tiền phòng hoặc khoản phụ thu khác).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dichVu()
    {
        return $this->belongsTo(DichVu::class, 'maDichVu', 'maDichVu');
    }
}
