<?php

namespace App\Http\Requests;

use App\Models\PhongTro;
use App\Models\KhachThue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuHopDongRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi yêu cầu này không.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $giaTriGop = [];
        if ($this->has('giaThueChot') && is_string($this->giaThueChot)) {
            $giaTriGop['giaThueChot'] = str_replace(['.', ','], '', $this->giaThueChot);
        }
        if ($this->has('tienDatCoc') && is_string($this->tienDatCoc)) {
            $giaTriGop['tienDatCoc'] = str_replace(['.', ','], '', $this->tienDatCoc);
        }
        if (!empty($giaTriGop)) {
            $this->merge($giaTriGop);
        }
    }

    /**
     * Quy tắc xác thực dữ liệu khi lập hợp đồng thuê phòng mới.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'maPhong' => [
                'required',
                'integer',
                Rule::exists('PhongTro', 'maPhong')->where(function ($query) {
                    $query->where('dangHoatDong', 1);
                }),
            ],
            'maKhach' => [
                'required',
                'integer',
                Rule::exists('KhachThue', 'maKhach')->where(function ($query) {
                    $query->where('dangHoatDong', 1);
                }),
            ],
            'ngayBatDau' => ['required', 'date'],
            'ngayKetThuc' => ['nullable', 'date', 'after_or_equal:ngayBatDau'],
            'giaThueChot' => ['required', 'numeric', 'min:0'],
            'tienDatCoc' => ['required', 'numeric', 'min:0'],
            'ghiChu' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Kiểm tra bổ sung nghiệp vụ trước khi lưu.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('maPhong')) {
                $phong = PhongTro::find($this->input('maPhong'));
                if ($phong) {
                    if ($phong->trangThaiPhong === 'DANG_SUA_CHUA') {
                        $validator->errors()->add('maPhong', 'Phòng này đang sửa chữa, không thể lập hợp đồng mới.');
                    } elseif ($phong->trangThaiPhong === 'DANG_THUE' || $phong->hopDongHieuLuc) {
                        $validator->errors()->add('maPhong', 'Phòng này đang có người thuê, không thể lập hợp đồng mới.');
                    }
                }
            }

            // Người đại diện phải là khách chưa ở phòng nào (không đứng tên HĐ khác, không là người ở cùng)
            if ($this->filled('maKhach')) {
                $khach = KhachThue::find($this->input('maKhach'));
                if ($khach && $khach->dangOPhong()) {
                    $validator->errors()->add('maKhach', "Khách {$khach->hoTen} hiện đang ở một phòng khác (đại diện hoặc ở cùng), không thể đứng tên hợp đồng mới.");
                }
            }
        });
    }

    /**
     * Thông báo lỗi tùy chỉnh bằng tiếng Việt.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'maPhong.required' => 'Vui lòng chọn phòng trọ cho thuê.',
            'maPhong.exists' => 'Phòng trọ được chọn không hợp lệ hoặc đã ngừng hoạt động.',
            'maKhach.required' => 'Vui lòng chọn người đại diện ký hợp đồng.',
            'maKhach.exists' => 'Khách thuê được chọn không tồn tại hoặc đã ngừng hoạt động.',
            'ngayBatDau.required' => 'Vui lòng chọn ngày bắt đầu hợp đồng.',
            'ngayBatDau.date' => 'Ngày bắt đầu hợp đồng không đúng định dạng ngày tháng.',
            'ngayKetThuc.date' => 'Ngày kết thúc hợp đồng không đúng định dạng ngày tháng.',
            'ngayKetThuc.after_or_equal' => 'Ngày kết thúc phải diễn ra sau hoặc trùng với ngày bắt đầu hợp đồng.',
            'giaThueChot.required' => 'Vui lòng nhập giá thuê chốt mỗi tháng.',
            'giaThueChot.numeric' => 'Giá thuê chốt phải là số hợp lệ.',
            'giaThueChot.min' => 'Giá thuê chốt không được âm.',
            'tienDatCoc.required' => 'Vui lòng nhập số tiền đặt cọc giữ chân.',
            'tienDatCoc.numeric' => 'Tiền đặt cọc phải là số hợp lệ.',
            'tienDatCoc.min' => 'Tiền đặt cọc không được âm.',
            'ghiChu.max' => 'Ghi chú không được vượt quá 255 ký tự.',
        ];
    }
}
