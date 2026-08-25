<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle(@js($getStatePath())) }" {{ $getExtraAttributeBag() }}>
        <div class="flex flex-row gap-2">
            @for($i = 1 ; $i <= 5 ; $i++) <label class="hover:scale-110" @mouseenter="state={{ $i }}">
                <input type="radio" x-model="state" value="{{ $i }}" class="sr-only">
                <flux:icon.star class="size-8" x-show="state < {{ $i }}" />
                <flux:icon.star class="size-8 text-amber-500 " x-show="state >= {{ $i }}" variant="solid" />
                </label>
                @endfor
        </div>
    </div>
</x-dynamic-component>
