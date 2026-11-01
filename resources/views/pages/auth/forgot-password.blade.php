<x-layouts::auth :title="__('ลืมรหัสผ่าน')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('ลืมรหัสผ่าน')" :description="__('กรอกอีเมลของคุณเพื่อรับลิงก์ตั้งรหัสผ่านใหม่')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email" :value="old('email')" autocomplete="email"
                :label="__('อีเมล')"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('ส่งลิงก์ตั้งรหัสผ่านใหม่') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('กลับไปหน้า') }}</span>
            <flux:link :href="route('login')">{{ __('เข้าสู่ระบบ') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
