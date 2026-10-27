<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiSoDienNuoc extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'ChiSoDienNuoc';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maChiSo';

    /**
     * Không sử dụng created_at và updated_at mặc định của Laravel.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Các trường được phép gán giá trị hàng loạt.
     * LƯU Ý: Cột 'soDienTieuThu' và 'soNuocTieuThu' là cột GENERATED ALWAYS AS (chiSo...Moi - chiSo...Cu) STORED,
     * tuyệt đối KHÔNG đưa vào $fillable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'maPhong',
        'thangGhiNhan',
        'chiSoDienCu',
        'chiSoDienMoi',
        'chiSoNuocCu',
        'chiSoNuocMoi',
        'ngayGhi',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'thangGhiNhan' => 'date',
        'chiSoDienCu' => 'integer',
        'chiSoDienMoi' => 'integer',
        'chiSoNuocCu' => 'integer',
        'chiSoNuocMoi' => 'integer',
        'soDienTieuThu' => 'integer',
        'soNuocTieuThu' => 'integer',
        'ngayGhi' => 'datetime',
    ];

    /**
     * Phòng trọ ghi nhận chỉ số điện nước.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function phongTro()
    {
        return $this->belongsTo(PhongTro::class, 'maPhong', 'maPhong');
    }

    /**
     * Các hóa đơn gắn liền với chỉ số điện nước này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hoaDon()
    {
        return $this->hasMany(HoaDon::class, 'maChiSo', 'maChiSo');
    }
}
