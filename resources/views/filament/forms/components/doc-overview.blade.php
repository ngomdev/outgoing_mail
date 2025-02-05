@php
    $parapheurs = $getRecord()->parapheurs;

    $validatorCount = 0;

    $isCancelled = $getRecord()->status === App\Enums\DocStatus::CANCELLED;
    $rank = $getRecord()->status->getRank();

    foreach ($parapheurs as $parapheur) {
        $lastValidation = $parapheur->user->lastDocValidationHistory($getRecord());
        if ($lastValidation && $lastValidation->is_active) {
            $validatorCount++;
        }
    }
@endphp

<ol class="flex items-center w-full mt-10">
    <div class="flex flex-col w-full">
        <p>{{ App\Enums\DocStatus::DRAFT->getLabel() }}</p>
        <li
            class="flex w-full items-center
            {{ $rank > 1 && $rank < 8 ? 'text-emerald-400' : 'text-gray-500' }}
            after:content-[''] after:w-full after:h-1 after:border-b
            {{ $rank > 1 && $rank < 8 ? 'after:border-emerald-400' : 'after:border-gray-200' }}
            after:border-4 after:inline-block">
            <span
                class="flex items-center justify-center w-10 h-10
                {{ $rank > 1 && $rank < 8 ? 'bg-emerald-400' : 'bg-gray-200' }}
                rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($rank === 1)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-amber-500' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @endif
            </span>
        </li>
    </div>

    <div class="flex flex-col w-full">
        <p>{{ App\Enums\DocStatus::INITIATED->getLabel() }}</p>
        <li
            class="flex w-full items-center
            {{ $rank >= 2 && $rank < 8 ? 'text-emerald-400' : 'text-gray-200' }}
            after:content-[''] after:w-full after:h-1 after:border-b
            {{ $rank >= 2 && $rank < 8 ? 'after:border-emerald-400' : 'after:border-gray-200' }}
            after:border-4 after:inline-block">
            <span
                class="flex items-center justify-center w-10 h-10
                {{ $rank >= 2 && $rank < 8 ? 'bg-emerald-400' : 'bg-gray-200' }}
                rounded-full lg:h-12 lg:w-12 shrink-0">

                @if ($rank >= 2 && $rank < 8)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-500' />
                @endif

            </span>
        </li>
    </div>

    @if ($isCancelled)
        <div class="flex flex-col w-full">
            <p>{{ App\Enums\DocStatus::CANCELLED->getLabel() }}</p>
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
        <p>Validation ({{ $validatorCount }}/{{ $parapheurs->count() }})</p>
        <li
            class="flex w-full items-center
            {{ $rank > 3 && $rank < 8 ? 'text-emerald-400' : 'text-gray-200' }}
            after:content-[''] after:w-full after:h-1 after:border-b
            {{ $getRecord()->status === App\Enums\DocStatus::SIGNED ? 'after:border-emerald-400' : 'after:border-gray-200' }}
            after:border-4 after:inline-block">
            <span
                class="flex items-center justify-center w-10 h-10
                {{ $rank > 3 && $rank < 8 ? 'bg-emerald-400' : 'bg-gray-200' }}
                rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($rank === 2 || $rank === 8)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-amber-400' />
                @elseif($rank <= 3)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-500' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @endif
            </span>
        </li>
    </div>

    <div class="flex flex-col w-full">
        <p>{{ $getRecord()->status === App\Enums\DocStatus::SIGNED ? App\Enums\DocStatus::SIGNED->getLabel() : 'Attente signature' }}
        </p>
        <li
            class="flex w-full items-center
            {{ $getRecord()->status === App\Enums\DocStatus::SIGNED ? 'text-emerald-400' : 'text-gray-200' }}">
            <span
                class="flex items-center justify-center w-10 h-10
                {{ $getRecord()->status === App\Enums\DocStatus::SIGNED ? 'bg-emerald-400' : 'bg-gray-200' }}
                rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($rank === 5)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @elseif($getRecord()->status === App\Enums\DocStatus::SIGNED)
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-white' />
                @else
                    <x-filament::icon icon='heroicon-m-check' class='h-6 w-6 text-gray-500' />
                @endif
            </span>
        </li>
    </div>

</ol>
