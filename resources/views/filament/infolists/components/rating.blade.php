<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div {{ $getExtraAttributeBag() }}>
        <div class="flex flex-row gap-2">
            @for($i = 1 ; $i <= 5 ; $i++) <label class="hover:scale-110">
                @if($i
                <= $getState()) <flux:icon.star class="size-8 text-amber-500 " variant="solid" />
                @else
                <flux:icon.star class="size-8" />
                @endif
                </label>
                @endfor
        </div>
    </div>
</x-dynamic-component>
