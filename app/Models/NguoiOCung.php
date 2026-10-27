<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguoiOCung extends Model
{
    use HasFactory;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'NguoiOCung';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maNguoiO';

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
        'hoTen',
        'soCCCD',
        'soDienThoai',
        'quanHe',
    ];

    /**
     * Hợp đồng thuê phòng chứa người ở cùng này.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hopDong()
    {
        return $this->belongsTo(HopDong::class, 'maHopDong', 'maHopDong');
    }
}
