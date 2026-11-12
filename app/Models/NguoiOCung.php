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
    protected $primaryKey = 'maNguoiOCung';

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
        'maKhach',
        'hoTen',
        'soDienThoai',
        'soCCCD',
        'ngayVaoO',
        'ghiChu',
        'dangHoatDong',
    ];

    /**
     * Đảm bảo bảng NguoiOCung hỗ trợ nhập thủ công (hoTen, soDienThoai, soCCCD, maKhach nullable).
     */
    public static function damBaoCauTrucBang(): void
    {
        static $daKiemTra = false;
        if ($daKiemTra) {
            return;
        }
        $daKiemTra = true;

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('NguoiOCung') && !\Illuminate\Support\Facades\Schema::hasColumn('NguoiOCung', 'hoTen')) {
                \Illuminate\Support\Facades\Schema::table('NguoiOCung', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('hoTen', 100)->nullable()->after('maKhach');
                    $table->string('soDienThoai', 20)->nullable()->after('hoTen');
                    $table->string('soCCCD', 20)->nullable()->after('soDienThoai');
                });

                \Illuminate\Support\Facades\DB::statement('ALTER TABLE NguoiOCung MODIFY maKhach INT UNSIGNED NULL');

                \Illuminate\Support\Facades\DB::statement('
                    UPDATE NguoiOCung noc
                    INNER JOIN KhachThue kt ON noc.maKhach = kt.maKhach
                    SET noc.hoTen = COALESCE(noc.hoTen, kt.hoTen),
                        noc.soDienThoai = COALESCE(noc.soDienThoai, kt.soDienThoai),
                        noc.soCCCD = COALESCE(noc.soCCCD, kt.soCCCD)
                ');
            }
        } catch (\Throwable $e) {
            // Bỏ qua nếu không thể alter
        }
    }

    /**
     * Lấy họ tên hiển thị của người ở cùng (ưu tiên hoTen nhập thủ công, fallback sang khachThue).
     */
    public function getTenHienThiAttribute(): string
    {
        return $this->hoTen ?: (optional($this->khachThue)->hoTen ?: 'Khách ở cùng');
    }

    /**
     * Lấy SĐT hiển thị của người ở cùng.
     */
    public function getSdtHienThiAttribute(): ?string
    {
        return $this->soDienThoai ?: optional($this->khachThue)->soDienThoai;
    }

    /**
     * Lấy CCCD hiển thị của người ở cùng.
     */
    public function getCccdHienThiAttribute(): ?string
    {
        return $this->soCCCD ?: optional($this->khachThue)->soCCCD;
    }

    /**
     * Ép kiểu dữ liệu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'maHopDong' => 'integer',
        'maKhach' => 'integer',
        'ngayVaoO' => 'date',
        'dangHoatDong' => 'integer',
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

    /**
     * Thông tin hồ sơ khách thuê ở cùng.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function khachThue()
    {
        return $this->belongsTo(KhachThue::class, 'maKhach', 'maKhach');
    }
}

