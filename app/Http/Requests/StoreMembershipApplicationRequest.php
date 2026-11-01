<?php

namespace App\Http\Requests;

use App\Models\MembershipApplication;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembershipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && ! MembershipApplication::where('user_id', $this->user()->id)->exists();
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $isEmployee = $this->input('membership_type') === 'ordinary' && in_array($this->input('ordinary_category'), ['lecturer', 'staff'], true);

        return [
            'membership_type' => ['required', Rule::in(array_keys(MembershipApplication::TYPES))],
            'ordinary_category' => ['exclude_unless:membership_type,ordinary', 'required', Rule::in(array_keys(MembershipApplication::ORDINARY_CATEGORIES))],
            'prefix' => ['required', Rule::in(['นาย', 'นาง', 'นางสาว', 'other'])],
            'prefix_other' => ['exclude_unless:prefix,other', 'required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'student_code' => [Rule::excludeIf($isEmployee), 'nullable', 'string', 'max:30'],
            'program' => [Rule::excludeIf($isEmployee), 'required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'national_id' => ['bail', 'required', 'regex:/^[0-9]{13}$/', function (string $attribute, mixed $value, Closure $fail): void {
                $sum = 0;
                for ($index = 0; $index < 12; $index++) {
                    $sum += (int) $value[$index] * (13 - $index);
                }
                if ((11 - ($sum % 11)) % 10 !== (int) $value[12]) {
                    $fail('เลขประจำตัวประชาชนไม่ถูกต้อง');
                }
                if (MembershipApplication::where('national_id_hash', hash_hmac('sha256', $value, config('app.key')))->exists()) {
                    $fail('เลขประจำตัวประชาชนนี้มีคำขอสมัครแล้ว');
                }
            }],
            'address' => ['required', 'string', 'max:2000'],
            'phone' => ['required', 'regex:/^[0-9+()\s-]{9,30}$/'],
            'acknowledged' => ['accepted'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ['required' => 'กรุณากรอก:attribute', 'string' => ':attributeต้องเป็นข้อความ', 'max' => ':attributeยาวเกินกำหนด', 'regex' => 'รูปแบบ:attributeไม่ถูกต้อง', 'ordinary_category.in' => 'กรุณาเลือกประเภทสมาชิกสามัญที่ถูกต้อง', 'prefix.in' => 'กรุณาเลือกคำนำหน้าจากรายการ', 'in' => 'กรุณาเลือกประเภทสมาชิกที่เปิดรับสมัคร', 'accepted' => 'กรุณายืนยันว่าได้อ่านข้อบังคับและรับทราบการใช้ข้อมูล'];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['membership_type' => 'ประเภทสมาชิก', 'ordinary_category' => 'ประเภทสมาชิกสามัญ', 'prefix' => 'คำนำหน้า', 'prefix_other' => 'คำนำหน้าเพิ่มเติม', 'first_name' => 'ชื่อ', 'last_name' => 'นามสกุล', 'student_code' => 'รหัสนิสิต', 'program' => 'หลักสูตร', 'nickname' => 'ชื่อเล่น', 'national_id' => 'เลขประจำตัวประชาชน', 'address' => 'ที่อยู่', 'phone' => 'เบอร์โทรศัพท์'];
    }
}
