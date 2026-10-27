<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Bảng cơ sở dữ liệu tương ứng.
     *
     * @var string
     */
    protected $table = 'NguoiDung';

    /**
     * Khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'maNguoiDung';

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
        'email',
        'matKhau',
        'vaiTro',
        'dangHoatDong',
        'ngayTao',
    ];

    /**
     * Các trường bị ẩn khi chuyển đổi thành mảng hoặc JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'matKhau',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dangHoatDong' => 'boolean',
        'ngayTao' => 'datetime',
    ];

    /**
     * Lấy mật khẩu để xác thực tài khoản.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->matKhau;
    }

    /**
     * Kiểm tra người dùng có phải là chủ trọ hay không.
     *
     * @return bool
     */
    public function laChuTro(): bool
    {
        return $this->vaiTro === 'CHU_TRO';
    }

    /**
     * Kiểm tra người dùng có phải là khách thuê hay không.
     *
     * @return bool
     */
    public function laKhachThue(): bool
    {
        return $this->vaiTro === 'KHACH_THUE';
    }

    /**
     * Quan hệ 1-1 với hồ sơ khách thuê.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function khachThue()
    {
        return $this->hasOne(KhachThue::class, 'maNguoiDung', 'maNguoiDung');
    }
}
