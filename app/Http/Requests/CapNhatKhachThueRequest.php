<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CapNhatKhachThueRequest extends FormRequest
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
        $maKhach = $this->route('maKhach') ?? $this->maKhach;

        return [
            'hoTen' => [
                'required',
                'string',
                'max:100',
            ],
            'ngaySinh' => [
                'nullable',
                'date',
                'before:today',
            ],
            'gioiTinh' => [
                'nullable',
                'in:NAM,NU,KHAC',
            ],
            'soCCCD' => [
                'required',
                'string',
                'digits:12',
                Rule::unique('KhachThue', 'soCCCD')->ignore($maKhach, 'maKhach'),
            ],
            'ngayCapCCCD' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'noiCapCCCD' => [
                'nullable',
                'string',
                'max:150',
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
            'ngheNghiep' => [
                'nullable',
                'string',
                'max:100',
            ],
            'bienSoXe' => [
                'nullable',
                'string',
                'max:30',
            ],
            'nguoiLienHeKhanCap' => [
                'nullable',
                'string',
                'max:100',
            ],
            'sdtKhanCap' => [
                'nullable',
                'string',
                'max:15',
            ],
            'quanHeKhanCap' => [
                'nullable',
                'string',
                'max:50',
            ],
            'daDangKyTamTru' => [
                'nullable',
                'boolean',
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
            'ngaySinh.date' => 'Ngày sinh không đúng định dạng ngày tháng.',
            'ngaySinh.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'gioiTinh.in' => 'Giới tính được chọn không hợp lệ.',
            'soCCCD.required' => 'Vui lòng nhập số căn cước công dân (CCCD).',
            'soCCCD.digits' => 'Số CCCD phải bao gồm đúng 12 chữ số.',
            'soCCCD.unique' => 'Số CCCD này đã tồn tại trong hệ thống hồ sơ khách thuê.',
            'ngayCapCCCD.date' => 'Ngày cấp CCCD không đúng định dạng ngày tháng.',
            'ngayCapCCCD.before_or_equal' => 'Ngày cấp CCCD không thể lớn hơn ngày hiện tại.',
            'noiCapCCCD.max' => 'Nơi cấp CCCD không được vượt quá 150 ký tự.',
            'soDienThoai.required' => 'Vui lòng nhập số điện thoại liên lạc.',
            'soDienThoai.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'diaChi.max' => 'Địa chỉ thường trú không được vượt quá 255 ký tự.',
            'ngheNghiep.max' => 'Nghề nghiệp không được vượt quá 100 ký tự.',
            'bienSoXe.max' => 'Biển số xe không được vượt quá 30 ký tự.',
            'nguoiLienHeKhanCap.max' => 'Tên người liên hệ khẩn cấp không được vượt quá 100 ký tự.',
            'sdtKhanCap.max' => 'Số điện thoại khẩn cấp không được vượt quá 15 ký tự.',
            'quanHeKhanCap.max' => 'Mối quan hệ khẩn cấp không được vượt quá 50 ký tự.',
        ];
    }
}
