@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="SIPBAR" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-12 items-center justify-center">
            <img src="/build/assets/logosmkn.png" alt="Logo SMKN 1 Bangsri" class="size-11 object-contain" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="SIPBAR" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-12 items-center justify-center">
            <img src="/build/assets/logosmkn.png" alt="Logo SMKN 1 Bangsri" class="size-11 object-contain" />
        </x-slot>
    </flux:brand>
@endif
