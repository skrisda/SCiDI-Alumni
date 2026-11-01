<?php

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('ตั้งค่าข้อมูลส่วนตัว')] class extends Component
{
    use ProfileValidationRules;

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

    public string $name = '';

    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('บันทึกข้อมูลส่วนตัวเรียบร้อยแล้ว'));
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">



    <flux:heading level="2" class="sr-only">{{ __('ตั้งค่าข้อมูลส่วนตัว') }}</flux:heading>

    <x-pages::settings.layout :show-sidebar="false" :heading="__('ข้อมูลส่วนตัว')" :subheading="__('แก้ไขชื่อและอีเมลสำหรับบัญชีของคุณ')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('ชื่อ–นามสกุล')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('อีเมล')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('อีเมลของคุณยังไม่ได้รับการยืนยัน') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('ส่งอีเมลยืนยันอีกครั้ง') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('ส่งลิงก์ยืนยันไปยังอีเมลของคุณแล้ว') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('บันทึกการเปลี่ยนแปลง') }}
                    </flux:button>
                </div>

            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
