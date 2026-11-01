<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('ตั้งค่าการแสดงผล')] class extends Component
{
    //
}; ?>

<section class="w-full">


    <flux:heading level="2" class="sr-only">{{ __('ตั้งค่าการแสดงผล') }}</flux:heading>

    <x-pages::settings.layout :show-sidebar="false" :heading="__('การแสดงผล')" :subheading="__('เลือกธีมการแสดงผลที่เหมาะกับการใช้งานของคุณ')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('โหมดสว่าง') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('โหมดมืด') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('ตามการตั้งค่าอุปกรณ์') }}</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</section>
