<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LuuKhachThueRequest extends FormRequest
{
    /**
     * Xác định người dùng có quyền thực hiện yêu cầu này không.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->laChuTro();
    }

    /**
     * Lấy các quy tắc xác thực áp dụng cho yêu cầu.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'hoTen' => [
                'required',
                'string',
                'max:100',
            ],
            'soCCCD' => [
                'required',
                'string',
                'digits:12',
                'unique:KhachThue,soCCCD',
            ],
            'soDienThoai' => [
                'required',
                'string',
                'max:15',
            ],
            'diaChi' => [
                'nullable',
                'string',
                'max:255',
            ],
            'taoTaiKhoan' => [
                'nullable',
            ],
            'email' => [
                'required_if:taoTaiKhoan,1',
                'nullable',
                'email',
                'max:150',
                'unique:NguoiDung,email',
            ],
            'matKhau' => [
                'required_if:taoTaiKhoan,1',
                'nullable',
                'string',
                'min:6',
            ],
        ];
    }

    /**
     * Lấy các thông báo lỗi xác thực tùy chỉnh bằng tiếng Việt.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'hoTen.required' => 'Vui lòng nhập họ và tên khách thuê.',
            'hoTen.string' => 'Họ và tên phải là chuỗi ký tự hợp lệ.',
            'hoTen.max' => 'Họ và tên không được vượt quá 100 ký tự.',
            'soCCCD.required' => 'Vui lòng nhập số căn cước công dân (CCCD).',
            'soCCCD.digits' => 'Số CCCD phải bao gồm đúng 12 chữ số.',
            'soCCCD.unique' => 'Số CCCD này đã tồn tại trong hệ thống hồ sơ khách thuê.',
            'soDienThoai.required' => 'Vui lòng nhập số điện thoại liên lạc.',
            'soDienThoai.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'diaChi.max' => 'Địa chỉ thường trú không được vượt quá 255 ký tự.',
            'email.required_if' => 'Vui lòng nhập địa chỉ email để cấp tài khoản đăng nhập.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng bởi một tài khoản khác.',
            'matKhau.required_if' => 'Vui lòng nhập mật khẩu cho tài khoản khách thuê.',
            'matKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ];
    }
}

