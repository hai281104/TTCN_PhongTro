<?php

namespace App\Http\Requests;

use App\Models\HopDong;
use App\Models\KhachThue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuNguoiOCungRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Thêm người ở cùng: nhập trực tiếp thông tin người ở cùng (hoặc truyền maKhach tương thích ngược).
     *
     * @return array
     */
    public function rules()
    {
        return [
            'maHopDong' => [
                'required',
                'integer',
                Rule::exists('HopDong', 'maHopDong')->where('trangThaiHD', 'HIEU_LUC'),
            ],
            'hoTen' => [
                'required_without:maKhach',
                'nullable',
                'string',
                'max:100',
            ],
            'soDienThoai' => ['nullable', 'string', 'max:20'],
            'soCCCD' => ['nullable', 'string', 'max:20'],
            'maKhach' => [
                'nullable',
                'integer',
                Rule::exists('KhachThue', 'maKhach')->where('dangHoatDong', 1),
            ],
            'ngayVaoO' => ['nullable', 'date'],
            'ghiChu' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Kiểm tra nghiệp vụ: phòng còn chỗ trống.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $hopDong = HopDong::with('phongTro')->find($this->input('maHopDong'));

            if ($this->filled('maKhach')) {
                $khach = KhachThue::find($this->input('maKhach'));
                if ($khach && $khach->dangOPhong()) {
                    $validator->errors()->add('maKhach', "Khách {$khach->hoTen} hiện đang ở một phòng khác, không thể thêm làm người ở cùng.");
                }
            }

            if ($hopDong && !$hopDong->conChoTrong()) {
                $sucChua = optional($hopDong->phongTro)->soNguoiToiDa ?? 1;
                $soPhong = optional($hopDong->phongTro)->soPhong;
                $validator->errors()->add('maHopDong', "Phòng {$soPhong} đã đủ sức chứa tối đa ({$sucChua} người).");
            }
        });
    }

    public function messages()
    {
        return [
            'maHopDong.required' => 'Vui lòng chọn hợp đồng / phòng đang thuê.',
            'maHopDong.exists' => 'Hợp đồng được chọn không tồn tại hoặc đã kết thúc.',
            'hoTen.required_without' => 'Vui lòng nhập họ và tên người ở cùng.',
            'hoTen.max' => 'Họ và tên không được vượt quá 100 ký tự.',
            'ngayVaoO.date' => 'Ngày vào ở không đúng định dạng.',
            'ghiChu.max' => 'Quan hệ với người đại diện không được vượt quá 100 ký tự.',
        ];
    }
}
