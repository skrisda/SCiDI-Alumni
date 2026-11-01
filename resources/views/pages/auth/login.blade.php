<x-layouts::auth :title="__('เข้าสู่ระบบ')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('เข้าสู่ระบบ')" :description="__('กรอกอีเมลและรหัสผ่านเพื่อเข้าสู่บัญชีของคุณ')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />


        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('อีเมล')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('รหัสผ่าน')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('รหัสผ่าน')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')">
                        {{ __('ลืมรหัสผ่าน?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('จดจำการเข้าสู่ระบบ')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('เข้าสู่ระบบ') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('ยังไม่มีบัญชีผู้ใช้?') }}</span>
            <flux:link :href="route('register')">{{ __('สร้างบัญชีผู้ใช้') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
