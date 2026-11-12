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
        'ngaySinh',
        'gioiTinh',
        'soCCCD',
        'ngayCapCCCD',
        'noiCapCCCD',
        'soDienThoai',
        'diaChi',
        'ngheNghiep',
        'bienSoXe',
        'nguoiLienHeKhanCap',
        'sdtKhanCap',
        'quanHeKhanCap',
        'daDangKyTamTru',
        'dangHoatDong',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ngaySinh' => 'date',
        'ngayCapCCCD' => 'date',
        'daDangKyTamTru' => 'boolean',
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
     * Hợp đồng đang có hiệu lực hiện tại của khách thuê (với tư cách người đại diện).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hopDongHieuLuc()
    {
        return $this->hasOne(HopDong::class, 'maKhach', 'maKhach')->where('trangThaiHD', 'HIEU_LUC');
    }

    /**
     * Danh sách liên kết người ở cùng đang hoạt động.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nguoiOCung()
    {
        return $this->hasMany(NguoiOCung::class, 'maKhach', 'maKhach')->where('dangHoatDong', 1);
    }

    /**
     * Bản ghi liên kết người ở cùng hiện tại.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nguoiOCungHienTai()
    {
        return $this->hasOne(NguoiOCung::class, 'maKhach', 'maKhach')->where('dangHoatDong', 1);
    }

    /**
     * Phạm vi truy vấn: khách đang hoạt động và CHƯA ở phòng nào
     * (không đứng tên hợp đồng hiệu lực, không là người ở cùng của hợp đồng hiệu lực).
     *
     * @param \Illuminate\Database\Eloquent\Builder $truyVan
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChuaOPhongNao($truyVan)
    {
        return $truyVan->where('dangHoatDong', 1)
            ->whereDoesntHave('hopDongHieuLuc')
            ->whereDoesntHave('nguoiOCungHienTai.hopDong', function ($sub) {
                $sub->where('trangThaiHD', 'HIEU_LUC');
            });
    }

    /**
     * Kiểm tra khách hiện có đang ở phòng nào không (đại diện hoặc ở cùng).
     *
     * @return bool
     */
    public function dangOPhong()
    {
        return $this->hopDongHieuLuc()->exists()
            || $this->nguoiOCungHienTai()->whereHas('hopDong', function ($sub) {
                $sub->where('trangThaiHD', 'HIEU_LUC');
            })->exists();
    }

    /**
     * Lấy thông tin phòng trọ và hợp đồng đang ở hiện tại của khách (kể cả đại diện hoặc ở cùng).
     *
     * @return array|null
     */
    public function layThongTinPhongHienTai()
    {
        if ($this->hopDongHieuLuc && $this->hopDongHieuLuc->phongTro) {
            return [
                'laDaiDien' => true,
                'vaiTro' => 'Đại diện',
                'phong' => $this->hopDongHieuLuc->phongTro,
                'phongTro' => $this->hopDongHieuLuc->phongTro,
                'hopDong' => $this->hopDongHieuLuc,
                'maHopDong' => $this->hopDongHieuLuc->maHopDong,
                'quanHe' => 'Người đại diện hợp đồng',
                'nguoiDaiDien' => $this,
                'nguoiOCung' => null,
            ];
        }

        if ($this->nguoiOCungHienTai && $this->nguoiOCungHienTai->hopDong && $this->nguoiOCungHienTai->hopDong->trangThaiHD === 'HIEU_LUC') {
            $hd = $this->nguoiOCungHienTai->hopDong;
            return [
                'laDaiDien' => false,
                'vaiTro' => 'Ở cùng',
                'phong' => $hd->phongTro,
                'phongTro' => $hd->phongTro,
                'hopDong' => $hd,
                'maHopDong' => $hd->maHopDong,
                'quanHe' => $this->nguoiOCungHienTai->ghiChu ?: 'Bạn cùng phòng',
                'nguoiDaiDien' => $hd->khachThue,
                'nguoiOCung' => $this->nguoiOCungHienTai,
            ];
        }

        return null;
    }
}
