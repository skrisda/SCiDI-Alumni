<?php

use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('ตั้งค่าความปลอดภัย')] class extends Component
{
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

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void {}

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('เปลี่ยนรหัสผ่านเรียบร้อยแล้ว'));
    }
}; ?>

<section class="w-full">


    <flux:heading level="2" class="sr-only">{{ __('ตั้งค่าความปลอดภัย') }}</flux:heading>

    <x-pages::settings.layout :show-sidebar="false" :heading="__('เปลี่ยนรหัสผ่าน')" :subheading="__('เลือกรหัสผ่านที่ยาวและคาดเดาได้ยากเพื่อความปลอดภัยของบัญชี')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('รหัสผ่านปัจจุบัน')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />
            <flux:input
                wire:model="password"
                :label="__('รหัสผ่านใหม่')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('ยืนยันรหัสผ่านใหม่')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-password-button">
                    {{ __('บันทึกการเปลี่ยนแปลง') }}
                </flux:button>
            </div>
        </form>


    </x-pages::settings.layout>

</section>
