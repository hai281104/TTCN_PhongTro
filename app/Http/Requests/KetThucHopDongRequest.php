<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KetThucHopDongRequest extends FormRequest
{
    /**
     * Xác định quyền gửi yêu cầu.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Quy tắc xác thực khi kết thúc / thanh lý hợp đồng.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ngayKetThuc' => ['required', 'date'],
            'tienHoanCoc' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ngayKetThuc.required' => 'Vui lòng chọn ngày trả phòng / kết thúc hợp đồng.',
            'ngayKetThuc.date' => 'Ngày kết thúc hợp đồng không đúng định dạng ngày tháng.',
            'tienHoanCoc.numeric' => 'Số tiền hoàn cọc phải là số hợp lệ.',
            'tienHoanCoc.min' => 'Số tiền hoàn cọc không được là số âm.',
        ];
    }
}
