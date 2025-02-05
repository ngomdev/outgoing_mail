@php
    $status = $getRecord()->status;
    $rank = $status->getRank();

    $isDelivered = $status === App\Enums\CourierStatus::DELIVERED;
    $isNotDelivered = $status === App\Enums\CourierStatus::NOT_DELIVERED;
    $isRejected = $status === App\Enums\CourierStatus::REJECTED;
    $isCancelled = $status === App\Enums\CourierStatus::CANCELLED;

    $ampliatairesQuery = $getRecord()->coursers->where('type', '!=', App\Enums\RecipientType::MAIN);
    $ampliatairesCount = $ampliatairesQuery->count();
    $deliveredAmpliatairesCount = $ampliatairesQuery->where('status', App\Enums\CourierStatus::DELIVERED)->count();
@endphp

<ol class="flex items-center w-full mt-10">
    <div class="flex flex-col w-full">
        <p>{{ App\Enums\CourierStatus::DRAFT->getLabel() }}</p>
        <li
            class="flex w-full items-center {{ $rank > 1 && $rank < 10 ? 'text-emerald-600' : 'text-gray-200' }} after:content-[''] after:w-full after:h-1 after:border-b {{ $rank > 1 && $rank < 10 ? 'after:border-emerald-400' : 'after:border-gray-200' }}  after:border-4 after:inline-block">
            <span
                class="flex items-center justify-center w-10 h-10 {{ $rank > 1 && $rank < 10 ? 'bg-emerald-400' : 'bg-gray-200' }} rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($rank === 1)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-amber-400' />
                @elseif ($rank > 1 && $rank < 10)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-600' />
                @endif
            </span>
        </li>
    </div>

    <div class="flex flex-col w-full">
        <p>{{ App\Enums\CourierStatus::INITIATED->getLabel() }}</p>
        <li
            class="flex w-full items-center {{ $rank >= 2 && $rank < 10 ? 'text-emerald-600' : 'text-gray-200' }} after:content-[''] after:w-full after:h-1 after:border-b {{ $rank >= 2 && $rank < 10 ? 'after:border-emerald-400' : 'after:border-gray-200' }}  after:border-4 after:inline-block">
            <span
                class="flex items-center justify-center w-10 h-10 {{ $rank >= 2 && $rank < 10 ? 'bg-emerald-400' : 'bg-gray-200' }} rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($rank >= 2 && $rank < 10)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-600' />
                @endif
            </span>
        </li>
    </div>

    @if ($isCancelled)
        <div class="flex flex-col w-full">
            <p>{{ App\Enums\CourierStatus::CANCELLED->getLabel() }}</p>
            <li
                class="flex w-full items-center text-red-300 after:content-[''] after:w-full after:h-1 after:border-b after:border-red-400 after:border-4 after:inline-block">
                <span
                    class="flex items-center justify-center w-10 h-10 bg-red-400 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <x-filament::icon icon='heroicon-m-x-mark' class='h-6 w-6 text-white' />
                </span>
            </li>
        </div>
    @endif

    <div class="flex flex-col w-full">
        <p>{{ App\Enums\CourierStatus::RETRIEVED->getLabel() }}</p>
        <li
            class="flex w-full items-center {{ $rank >= 4 && $rank < 10 ? 'text-emerald-600' : 'text-gray-200' }} after:content-[''] after:w-full after:h-1 after:border-b {{ $rank >= 4 && $rank < 10 ? 'after:border-emerald-400' : 'after:border-gray-200' }}  after:border-4 after:inline-block">
            <span
                class="flex items-center justify-center w-10 h-10 {{ $rank >= 4 && $rank < 10 ? 'bg-emerald-400' : 'bg-gray-200' }} rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($rank === 2)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-600' />
                @elseif ($rank >= 4 && $rank < 10)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-600' />
                @endif
            </span>
        </li>
    </div>

    <div class="flex flex-col w-full">
        <div class="flex flex-col">
            <p>{{ $isNotDelivered ? 'Non distribué!' : 'En cours de distribution' }}</p>
            <p class="text-xs">Ampliataires: <b>{{ $deliveredAmpliatairesCount }}/{{ $ampliatairesCount }}</b></p>
        </div>
        <li
            class="mb-3 flex w-full items-center {{ $isNotDelivered ? 'text-red-300' : ($rank > 4 && $rank < 10 ? 'text-emerald-600' : 'text-gray-200') }} after:content-[''] after:w-full after:h-1 after:border-b {{ $isNotDelivered ? 'after:border-red-400' : ($rank > 4 && $rank < 10 ? 'after:border-emerald-400' : 'after:border-gray-200') }}  after:border-4 after:inline-block">
            <span
                class="flex items-center justify-center w-10 h-10 {{ $isNotDelivered ? 'bg-red-400' : ($rank > 4 && $rank < 10 ? 'bg-emerald-400' : 'bg-gray-200') }} rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($isNotDelivered)
                    <x-filament::icon icon='heroicon-m-x-mark' class='h-6 w-6 text-white' />
                @elseif ($rank === 4)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-600' />
                @elseif ($rank >= 4)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-600' />
                @endif
            </span>
        </li>
    </div>

    <div class="flex flex-col w-full">
        <p>Courrier distribué</p>
        <li class="flex items-center w-full">
            <span
                class="flex items-center justify-center w-10 h-10 {{ $isNotDelivered ? 'bg-red-400' : ($isDelivered ? 'bg-emerald-400' : ($isRejected ? 'bg-red-400' : 'bg-gray-200')) }} rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($isNotDelivered)
                    <x-filament::icon icon='heroicon-m-map-pin' class='h-6 w-6 text-white' />
                @elseif ($isDelivered)
                    <x-filament::icon icon='heroicon-m-map-pin' class='h-6 w-6 text-white' />
                @elseif($isRejected)
                    <x-filament::icon icon='heroicon-m-map-pin' class='h-6 w-6 text-white' />
                @else
                    <x-filament::icon icon='heroicon-m-map-pin' class='h-6 w-6 text-gray-600' />
                @endif
            </span>
        </li>
    </div>

</ol>
