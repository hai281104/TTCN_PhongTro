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
     * Thêm người ở cùng: chọn hợp đồng đang hiệu lực + khách đã có hồ sơ.
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
            'maKhach' => [
                'required',
                'integer',
                Rule::exists('KhachThue', 'maKhach')->where('dangHoatDong', 1),
            ],
            'ngayVaoO' => ['nullable', 'date'],
            'ghiChu' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Kiểm tra nghiệp vụ: phòng còn chỗ và khách chưa ở phòng nào.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $hopDong = HopDong::with('phongTro')->find($this->input('maHopDong'));
            $khach = KhachThue::find($this->input('maKhach'));

            if ($khach && $khach->dangOPhong()) {
                $validator->errors()->add('maKhach', "Khách {$khach->hoTen} hiện đang ở một phòng khác, không thể thêm làm người ở cùng.");
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
            'maKhach.required' => 'Vui lòng chọn khách thuê ở cùng.',
            'maKhach.exists' => 'Khách thuê được chọn không tồn tại hoặc đã ngừng hoạt động.',
            'ngayVaoO.date' => 'Ngày vào ở không đúng định dạng.',
            'ghiChu.max' => 'Quan hệ với người đại diện không được vượt quá 100 ký tự.',
        ];
    }
}

