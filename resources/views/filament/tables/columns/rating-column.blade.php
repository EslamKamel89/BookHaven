<div {{ $getExtraAttributeBag()->class('flex items-center') }}>
    @if($getState())
        @php
            $state = round($getState());
        @endphp

        <div class="flex items-center gap-0.5">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $state)
                    <x-heroicon-s-star
                        class="h-5 w-5 text-amber-400"
                    />
                @else
                    <x-heroicon-o-star
                        class="h-5 w-5 text-gray-300 dark:text-gray-600"
                    />
                @endif
            @endfor
        </div>

        <span class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ number_format($getState(), 1) }}
        </span>
    @else
        <span class="text-sm text-gray-400 italic">
            No rating
        </span>
    @endif
</div>