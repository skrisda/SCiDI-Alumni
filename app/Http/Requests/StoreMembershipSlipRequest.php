<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipSlipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return ['slip' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ['slip.required' => 'กรุณาแนบหลักฐานการโอนเงิน', 'slip.file' => 'กรุณาแนบไฟล์ที่ถูกต้อง', 'slip.mimes' => 'รองรับไฟล์ JPG, PNG และ PDF เท่านั้น', 'slip.max' => 'ขนาดไฟล์ต้องไม่เกิน 5 MB'];
    }
}
