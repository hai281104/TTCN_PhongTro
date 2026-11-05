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
        'maLoaiPhong',
        'tang',
        'dienTich',
        'tienCocMacDinh',
        'tienNghi',
        'soNguoiToiDa',
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
        'maLoaiPhong' => 'integer',
        'dienTich' => 'decimal:2',
        'tienCocMacDinh' => 'decimal:0',
        'tienNghi' => 'array',
        'soNguoiToiDa' => 'integer',
        'donGiaMacDinh' => 'decimal:0',
        'dangHoatDong' => 'boolean',
    ];

    /**
     * Danh mục tiện nghi nội thất hệ thống hỗ trợ.
     *
     * @return array<string, array<string, string>>
     */
    public static function layDanhSachTienNghiHeThong()
    {
        return [
            'dieu_hoa' => ['ten' => 'Điều hòa', 'bieuTuong' => ''],
            'nong_lanh' => ['ten' => 'Bình nóng lạnh', 'bieuTuong' => ''],
            'tu_lanh' => ['ten' => 'Tủ lạnh', 'bieuTuong' => ''],
            'giuong_nem' => ['ten' => 'Giường nệm', 'bieuTuong' => ''],
            'tu_quan_ao' => ['ten' => 'Tủ quần áo', 'bieuTuong' => ''],
            'ke_bep' => ['ten' => 'Kệ bếp', 'bieuTuong' => ''],
            'gac_lung' => ['ten' => 'Gác lửng', 'bieuTuong' => ''],
            'ban_cong' => ['ten' => 'Ban công', 'bieuTuong' => ''],
            'may_giat' => ['ten' => 'Máy giặt', 'bieuTuong' => ''],
            'khoa_van_tay' => ['ten' => 'Khóa vân tay', 'bieuTuong' => ''],
        ];
    }

    /**
     * Lấy tên hiển thị của tiện nghi từ mã key.
     *
     * @param string $key
     * @return string
     */
    public static function layTenTienNghi($key)
    {
        $danhSach = self::layDanhSachTienNghiHeThong();
        if (isset($danhSach[$key])) {
            return $danhSach[$key]['ten'];
        }
        if ($key === 'binh_nong_lanh') {
            return 'Bình nóng lạnh';
        }
        if ($key === 'ban_ghe') {
            return 'Bàn ghế làm việc';
        }
        return ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * Thể loại của phòng trọ.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'maLoaiPhong', 'maLoaiPhong');
    }

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
     * Danh sách ghi nhận chỉ số điện nước của phòng.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chiSoDienNuoc()
    {
        return $this->hasMany(ChiSoDienNuoc::class, 'maPhong', 'maPhong');
    }
}
