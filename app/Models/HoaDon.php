<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'HoaDon';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maHoaDon';

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
        'maHopDong',
        'maChiSo',
        'thangNam',
        'ngayLap',
        'hanThanhToan',
        'tongTien',
        'trangThaiThanhToan',
        'ngayThanhToan',
        'ghiChu',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'thangNam' => 'date',
        'ngayLap' => 'date',
        'hanThanhToan' => 'date',
        'tongTien' => 'decimal:0',
        'ngayThanhToan' => 'date',
    ];

    /**
     * Hợp đồng thuê phòng liên quan đến hóa đơn này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hopDong()
    {
        return $this->belongsTo(HopDong::class, 'maHopDong', 'maHopDong');
    }

    /**
     * Bản ghi chỉ số điện nước dùng để tính tiền điện nước cho hóa đơn này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chiSoDienNuoc()
    {
        return $this->belongsTo(ChiSoDienNuoc::class, 'maChiSo', 'maChiSo');
    }

    /**
     * Danh sách các khoản mục chi tiết trong hóa đơn.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chiTietHoaDon()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'maHoaDon', 'maHoaDon');
    }
}
