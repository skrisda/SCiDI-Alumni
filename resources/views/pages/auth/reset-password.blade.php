<x-layouts::auth :title="__('ตั้งรหัสผ่านใหม่')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('ตั้งรหัสผ่านใหม่')" :description="__('กำหนดรหัสผ่านใหม่สำหรับบัญชีของคุณ')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                :value="old('email', request('email'))"
                :label="__('อีเมล')"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('รหัสผ่าน')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('รหัสผ่าน')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('ยืนยันรหัสผ่าน')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('ยืนยันรหัสผ่าน')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    {{ __('ตั้งรหัสผ่านใหม่') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
