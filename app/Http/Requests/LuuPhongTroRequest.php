<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LuuPhongTroRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $duLieuGop = [];

        if ($this->has('donGiaMacDinh') && is_string($this->donGiaMacDinh)) {
            $duLieuGop['donGiaMacDinh'] = str_replace(['.', ','], '', $this->donGiaMacDinh);
        }

        if ($this->has('tienCocMacDinh') && is_string($this->tienCocMacDinh)) {
            $duLieuGop['tienCocMacDinh'] = str_replace(['.', ','], '', $this->tienCocMacDinh);
        }

        if ($this->has('tang') && $this->tang !== null) {
            $duLieuGop['tang'] = (string) $this->tang;
        }

        if (!empty($duLieuGop)) {
            $this->merge($duLieuGop);
        }
    }

    /**
     * Lấy các quy tắc xác thực áp dụng cho yêu cầu.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'soPhong' => [
                'required',
                'string',
                'max:20',
                'unique:PhongTro,soPhong',
            ],
            'maLoaiPhong' => [
                'required',
                'integer',
                'exists:LoaiPhong,maLoaiPhong',
            ],
            'tang' => [
                'nullable',
                'string',
                'max:20',
            ],
            'dienTich' => [
                'nullable',
                'numeric',
                'min:1',
                'max:999.99',
            ],
            'tienCocMacDinh' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'tienNghi' => [
                'nullable',
                'array',
            ],
            'tienNghi.*' => [
                'string',
                'max:50',
            ],
            'soNguoiToiDa' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
            'donGiaMacDinh' => [
                'required',
                'numeric',
                'min:0',
            ],
            'ghiChu' => [
                'nullable',
                'string',
                'max:255',
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
            'soPhong.required' => 'Vui lòng nhập số phòng.',
            'soPhong.string' => 'Số phòng phải là chuỗi ký tự hợp lệ.',
            'soPhong.max' => 'Số phòng không được vượt quá 20 ký tự.',
            'soPhong.unique' => 'Số phòng này đã tồn tại trong hệ thống.',
            'maLoaiPhong.required' => 'Vui lòng chọn thể loại phòng trọ.',
            'maLoaiPhong.integer' => 'Thể loại phòng không hợp lệ.',
            'maLoaiPhong.exists' => 'Thể loại phòng được chọn không tồn tại trong hệ thống.',
            'tang.max' => 'Tên tầng không được vượt quá 20 ký tự.',
            'dienTich.numeric' => 'Diện tích phòng phải là một con số hợp lệ.',
            'dienTich.min' => 'Diện tích phòng phải từ 1 m² trở lên.',
            'dienTich.max' => 'Diện tích phòng không hợp lệ.',
            'tienCocMacDinh.numeric' => 'Tiền cọc mặc định phải là số hợp lệ.',
            'tienCocMacDinh.min' => 'Tiền cọc mặc định không được âm.',
            'soNguoiToiDa.required' => 'Vui lòng nhập số người ở tối đa của phòng.',
            'soNguoiToiDa.integer' => 'Sức chứa tối đa phải là số nguyên.',
            'soNguoiToiDa.min' => 'Sức chứa tối đa phải có ít nhất 1 người.',
            'soNguoiToiDa.max' => 'Sức chứa tối đa không vượt quá 20 người.',
            'donGiaMacDinh.required' => 'Vui lòng nhập đơn giá mặc định của phòng.',
            'donGiaMacDinh.numeric' => 'Đơn giá mặc định phải là một số hợp lệ.',
            'donGiaMacDinh.min' => 'Đơn giá mặc định không được nhỏ hơn 0 đ.',
            'ghiChu.string' => 'Ghi chú phải là chuỗi ký tự.',
            'ghiChu.max' => 'Ghi chú không được vượt quá 255 ký tự.',
        ];
    }
}
