<?php

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    /** @var array<string, string> */
    protected $messages = [
        'required' => 'กรุณากรอก:attribute',
        'string' => ':attributeต้องเป็นข้อความ',
        'email' => 'กรุณากรอกอีเมลให้ถูกต้อง',
        'max.string' => ':attributeต้องมีความยาวไม่เกิน :max ตัวอักษร',
        'unique' => 'อีเมลนี้ถูกใช้งานแล้ว กรุณาใช้อีเมลอื่น',
        'current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
        'confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        'password.min' => 'รหัสผ่านต้องมีความยาวอย่างน้อย :min ตัวอักษร',
        'password.letters' => 'รหัสผ่านต้องมีตัวอักษรอย่างน้อยหนึ่งตัว',
        'password.mixed' => 'รหัสผ่านต้องมีทั้งตัวพิมพ์ใหญ่และตัวพิมพ์เล็ก',
        'password.numbers' => 'รหัสผ่านต้องมีตัวเลขอย่างน้อยหนึ่งตัว',
        'password.symbols' => 'รหัสผ่านต้องมีสัญลักษณ์อย่างน้อยหนึ่งตัว',
        'password.uncompromised' => 'รหัสผ่านนี้เคยปรากฏในข้อมูลรั่วไหล กรุณาใช้รหัสผ่านอื่น',
    ];

    /** @var array<string, string> */
    protected $validationAttributes = [
        'name' => 'ชื่อ–นามสกุล',
        'email' => 'อีเมล',
        'current_password' => 'รหัสผ่านปัจจุบัน',
        'password' => 'รหัสผ่าน',
        'password_confirmation' => 'การยืนยันรหัสผ่าน',
    ];


    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
    <form method="POST" wire:submit="deleteUser" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('ยืนยันการลบบัญชีผู้ใช้หรือไม่?') }}</flux:heading>

            <flux:subheading>
                {{ __('เมื่อลบบัญชี ข้อมูลทั้งหมดที่เกี่ยวข้องจะถูกลบอย่างถาวรและไม่สามารถกู้คืนได้ กรุณากรอกรหัสผ่านปัจจุบันเพื่อยืนยันการลบบัญชี') }}
            </flux:subheading>
        </div>

        <flux:input wire:model="password" :label="__('รหัสผ่านปัจจุบัน')" type="password" viewable />

        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <flux:modal.close>
                <flux:button variant="filled">{{ __('ยกเลิก') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" type="submit" data-test="confirm-delete-user-button">
                {{ __('ลบบัญชีผู้ใช้') }}
            </flux:button>
        </div>
    </form>
</flux:modal>
