<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CapNhatPhongTroRequest extends FormRequest
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
        if ($this->has('donGiaMacDinh') && is_string($this->donGiaMacDinh)) {
            $this->merge([
                'donGiaMacDinh' => str_replace(['.', ','], '', $this->donGiaMacDinh),
            ]);
        }
    }

    /**
     * Lấy các quy tắc xác thực áp dụng cho yêu cầu.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $maPhong = $this->route('maPhong') ?? $this->maPhong;

        return [
            'soPhong' => [
                'required',
                'string',
                'max:20',
                Rule::unique('PhongTro', 'soPhong')->ignore($maPhong, 'maPhong'),
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
            'donGiaMacDinh.required' => 'Vui lòng nhập đơn giá mặc định của phòng.',
            'donGiaMacDinh.numeric' => 'Đơn giá mặc định phải là một số hợp lệ.',
            'donGiaMacDinh.min' => 'Đơn giá mặc định không được nhỏ hơn 0 đ.',
            'ghiChu.string' => 'Ghi chú phải là chuỗi ký tự.',
            'ghiChu.max' => 'Ghi chú không được vượt quá 255 ký tự.',
        ];
    }
}

