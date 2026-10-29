<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemNguoiOCungRequest extends FormRequest
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
     * Quy tắc xác thực khi thêm người ở cùng phòng.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hoTen' => ['required', 'string', 'max:100'],
            'soCCCD' => ['nullable', 'string', 'digits:12'],
            'soDienThoai' => ['nullable', 'string', 'regex:/^[0-9]{10,11}$/'],
            'quanHe' => ['nullable', 'string', 'max:50'],
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
            'hoTen.required' => 'Vui lòng nhập họ và tên người ở cùng.',
            'hoTen.max' => 'Họ và tên không được vượt quá 100 ký tự.',
            'soCCCD.digits' => 'Số CCCD phải bao gồm đúng 12 chữ số.',
            'soDienThoai.regex' => 'Số điện thoại phải từ 10 đến 11 chữ số.',
            'quanHe.max' => 'Mối quan hệ không được vượt quá 50 ký tự.',
        ];
    }
}
