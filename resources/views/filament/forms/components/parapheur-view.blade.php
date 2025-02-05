@php
    $document = $getRecord()->document;
    $urgency = $document->doc_urgency;
    $user = $getRecord()->user;
    $isCurrentValidator = $document->currentValidator?->user_id === $user->id;
    $requestDate = $getRecord()->action_request_date
        ? Carbon\CarbonImmutable::parse($getRecord()->action_request_date)
        : null;
    $lastValidation = $user->lastDocValidationHistory($this->record);
    $hasValidated = $lastValidation?->is_active === true;
    $isDraft = $document->status === App\Enums\DocStatus::DRAFT;

    if ($requestDate) {
        $delayExpirationDate = $requestDate->copy()->addHours($urgency->getValue());

        $documentNumberService = new App\Services\DocumentNumberService();

        try {
            $timeDifference = $delayExpirationDate->diff(now(), true);

            if (
                $delayExpirationDate->isPast() ||
                $timeDifference->days < 0 ||
                $timeDifference->h < 0 ||
                $timeDifference->i < 0
            ) {
                $formattedRemainingTime =
                    'En retard de ' . $documentNumberService->formatRemainingTime($timeDifference, '');
                $color = 'danger'; // Late status color
            } elseif ($timeDifference->days === 0 && $timeDifference->h === 0 && $timeDifference->i === 0) {
                $formattedRemainingTime =
                    'En retard de ' . $documentNumberService->formatRemainingTime($timeDifference, '');
                $color = 'danger'; // Late status color
            } elseif ($timeDifference->days === 0 && $timeDifference->h === 0 && $timeDifference->i < 60) {
                $formattedRemainingTime = $timeDifference->i . ' minute' . ($timeDifference->i > 1 ? 's' : '');
                $color = 'success'; // Safe state color
            } else {
                $formattedRemainingTime = $documentNumberService->formatRemainingTime($timeDifference, '');
                $color = $documentNumberService->getColorBasedOnPercentage(
                    $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i,
                    $urgency->getValue(),
                );
            }
        } catch (Exception $e) {
            // Handle any exception, e.g., invalid date
            $formattedRemainingTime = $e->getMessage();
            $color = 'danger'; // Assuming no valid time remaining is a critical state
        }

        // $delayExpirationDate = $requestDate->addHours($urgency->getValue());
        // $timeDifference = $delayExpirationDate->diffInHours(now());
        // $percentageRemaining = ($timeDifference / $urgency->getValue()) * 100;
        // $color = $percentageRemaining > 50 ? 'success' : ($percentageRemaining <= 40 ? 'warning' : 'danger');

        // if ($timeDifference >= 48) {
        //     $formattedTime = $delayExpirationDate->diffInDays(now()) . ' jours';
        // } else {
        //     $formattedTime = $timeDifference . ' heures';
        // }
    }

@endphp

<div
    class="container grid grid-cols-6 items-center p-2 shadow-md rounded-xl border {{ $hasValidated ? 'border-emerald-400' : (!$hasValidated && $isCurrentValidator && !$isDraft ? 'border-amber-400' : 'border-gray-400') }}">
    <div class="section flex col-span-2">
        <x-filament::avatar src="{{ filament()->getUserAvatarUrl($user) }}" size="lg" class="inline mr-2" />

        <div class="user-info flex-col">
            <p class="text-sm text-nowrap">{{ $user->name }}</p>
            <p class="text-sm italic">{{ $user->email }}</p>
            @if (!$user->is_active)
                <x-filament::badge class='w-fit' size='lg' color='danger' icon='heroicon-m-x-circle'>
                    Inactif
                </x-filament::badge>
            @endif
        </div>
    </div>

    @if ($document->status !== App\Enums\DocStatus::DRAFT)
        <div class="section flex items-center justify-center col-span-1">
            @if ($isCurrentValidator && !$hasValidated)
                <x-filament::icon-button icon="heroicon-m-check-circle" color="warning" label="Statut validation" />
            @else
                <x-filament::icon-button icon="heroicon-m-check-circle" color="{{ $hasValidated ? 'success' : 'gray' }}"
                    label="Statut validation" />
            @endif

        </div>

        <div class="section flex-col col-span-3">

            <div class="grid grid-cols-2 items-center border-b py-1">
                <p class="text-xs col-span-1">Date d'affectation:</p>
                @if ($requestDate)
                    <x-filament::badge class='w-fit text-xs col-span-1' color='gray'>
                        {{ $requestDate?->format('d M Y - H:i') }}
                    </x-filament::badge>
                @else
                    <span class="border-b-2 w-2 border-black justify-center items-center"></span>
                @endif
            </div>

            @if ($hasValidated)
                <div class="grid grid-cols-2 items-center py-1">
                    <p class="text-xs col-span-1">Date validation:</p>
                    <x-filament::badge class='w-fit text-xs col-span-1 self-end' color='gray'>
                        <p class="text-end">{{ $lastValidation->created_at?->format('d M Y - H:i') }}</p>
                    </x-filament::badge>
                </div>
            @endif

            @if ($requestDate && !$hasValidated)
                <div class="grid grid-cols-2 items-center py-1">
                    <p class="text-xs col-span-1">Temps restant:</p>
                    <x-filament::badge class='w-fit text-xs col-span-1' color='{{ $color }}'
                        icon='{{ $urgency->getIcon() }}'>
                        <p class="break-all text-wrap">{{ $formattedRemainingTime }}</p>
                    </x-filament::badge>
                </div>
            @endif
        </div>
    @endif
</div>
