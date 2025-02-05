@php
    // $record = $getRecord();

    // if ($record instanceof App\Models\Courier) {
    //     $recipient = $record->mainRecipient;
    //     $courser = $record->mainCourser;
    //     $contact = $record->mainContact;

    //     $courierStatus = $record->status;

    //     $mainData = $record->coursers->where('type', App\Enums\RecipientType::MAIN)->first();

    //     $pickupDate = $mainData?->pickup_date;
    //     $depositDate = $mainData?->deposit_date;

    //     $receipt = $mainData?->receipt_path;

    //     $rejectionMotive = $mainData->rejection_motive;

    //     $assignmentDate = $mainData->assignment_date ? Carbon\CarbonImmutable::parse($mainData->assignment_date) : null;

    //     $isNotDelivered = $record->status === App\Enums\CourierStatus::NOT_DELIVERED;
    // } elseif ($record instanceof App\Models\CourierUser) {
    //     $recipient = $record->recipient;
    //     $courser = $record->courser;
    //     $contact = $record->contact;

    //     $courierStatus = $record->courier->status;

    //     $pickupDate = $record->pickup_date;
    //     $depositDate = $record->deposit_date;

    //     $receipt = $record->receipt_path;

    //     $rejectionMotive = $record->rejection_motive;

    //     $assignmentDate = $record->assignment_date ? Carbon\CarbonImmutable::parse($record->assignment_date) : null;

    //     $isNotDelivered = $record->status === App\Enums\CourierStatus::NOT_DELIVERED;
    // }

    // $isDelivered = $courierStatus === App\Enums\CourierStatus::DELIVERED;
    // $isRejected = $courierStatus === App\Enums\CourierStatus::REJECTED;

    // $isAwaitingPickup = $courierStatus !== App\Enums\CourierStatus::DRAFT && !$pickupDate;

    // $isAwaitingDelivery = $pickupDate && !$depositDate && !$isDelivered && !$isRejected;

    // $isDraft = $courierStatus === App\Enums\CourierStatus::DRAFT;

    // $receiptPath = asset("storage/$receipt");

    // // Handle late courser status
    // $recoveryDelaySetting = App\Models\Setting::where('key', App\Enums\SettingKeys::COURIER_RECOVERY_DELAY)->first();
    // if ($recoveryDelaySetting) {
    //     // take either specified value if active, or default value
    //     $recoveryDelay = $recoveryDelaySetting->is_active ? (int) $recoveryDelaySetting->value : (int) $recoveryDelaySetting->default_value;
    // } else {
    //     $recoveryDelay = 48;
    // }

    // if ($assignmentDate) {
    //     $delayExpirationDate = $assignmentDate->copy()->addHours($recoveryDelay);

    //     $documentNumberService = new App\Services\DocumentNumberService();

    //     try {
    //         $timeDifference = $delayExpirationDate->diff(now(), true);

    //         if ($delayExpirationDate->isPast()) {
    //             $formattedRemainingTime = $documentNumberService->formatRemainingTime($timeDifference, 'En retard de ');
    //             $color = 'danger'; // Late status color
    //         } else {
    //             $totalMinutes = $timeDifference->days * 24 * 60 + $timeDifference->h * 60 + $timeDifference->i;

    //             if ($totalMinutes <= 0) {
    //                 $formattedRemainingTime = $documentNumberService->formatRemainingTime($timeDifference, 'En retard de ');
    //                 $color = 'danger'; // Late status color
    //             } elseif ($totalMinutes < 60) {
    //                 $formattedRemainingTime = $totalMinutes . ' minute' . ($totalMinutes > 1 ? 's' : '');
    //                 $color = 'success'; // Safe state color
    //             } else {
    //                 $formattedRemainingTime = $documentNumberService->formatRemainingTime($timeDifference, '');
    //                 $color = $documentNumberService->getColorBasedOnPercentage($totalMinutes, $recoveryDelay);
    //             }
    //         }
    //     } catch (Exception $e) {
    //         // Handle any exception, e.g., invalid date
    //         $formattedRemainingTime = 'No valid time remaining';
    //         $color = 'danger'; // Assuming no valid time remaining is a critical state
    //     }
    // }

    $record = $getRecord();

    if ($record instanceof App\Models\Courier) {
        $mainData = $record->coursers->where('type', App\Enums\RecipientType::MAIN)->first();
    } elseif ($record instanceof App\Models\CourierUser) {
        $mainData = $record;
    }

    if ($mainData) {
        $recipient = $mainData->recipient;
        $courser = $mainData->courser;
        $contact = $mainData->contact;

        $assignmentDate = $mainData->assignment_date ? Carbon\CarbonImmutable::parse($mainData->assignment_date) : null;

        $courierStatus = $record instanceof App\Models\Courier ? $record->status : $record->courier->status;
        $deliveryStatus = $mainData->status;

        $pickupDate = $mainData->pickup_date;
        $depositDate = $mainData->deposit_date;
        $receipt = $mainData->receipt_path;
        $signature = $mainData->signature_path;

        // COURIER STATUSES
        $isDraft = $deliveryStatus === App\Enums\CourierStatus::DRAFT;
        $isRejected = $deliveryStatus === App\Enums\CourierStatus::REJECTED;
        $isNotDelivered = $deliveryStatus === App\Enums\CourierStatus::NOT_DELIVERED;
        $isDelivered = $deliveryStatus === App\Enums\CourierStatus::DELIVERED;
        $isAwaitingPickup = $deliveryStatus === App\Enums\CourierStatus::INITIATED;
        $isAwaitingDelivery = $deliveryStatus === App\Enums\CourierStatus::RETRIEVED;
        $isCancelled = $deliveryStatus === App\Enums\CourierStatus::CANCELLED;

        $rejectionMotive = $mainData->rejection_motive;

        $receiptPath = asset("storage/$receipt");
        $signaturePath = $signature ? asset("storage/$signature") : null;

        // Handle late courser status
        $recoveryDelaySetting = App\Models\Setting::where(
            'key',
            App\Enums\SettingKeys::COURIER_RECOVERY_DELAY,
        )->first();
        $recoveryDelay = $recoveryDelaySetting
            ? ($recoveryDelaySetting->is_active
                ? (int) $recoveryDelaySetting->value
                : (int) $recoveryDelaySetting->default_value)
            : 48;

        if ($assignmentDate) {
            $delayExpirationDate = $assignmentDate->copy()->addHours($recoveryDelay);

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
                        $recoveryDelay,
                    );
                }
            } catch (Exception $e) {
                // Handle any exception, e.g., invalid date
                $formattedRemainingTime = 'No valid time remaining';
                $color = 'danger'; // Assuming no valid time remaining is a critical state
            }
        }
    }
@endphp

@if ($mainData)
    <div class="container">
        <x-filament::section collapsible icon="heroicon-o-truck" icon-color="primary">
            <x-slot name="heading">
                {{ $getLabel() }}:
                <div class="flex flex-col col-span-1">
                    <span class="text-cyan-400 text-lg mr-2">{{ $recipient->name }} {{ $mainData->id }}</span>
                    @if (!$recipient->is_active)
                        <x-filament::badge class='w-fit mb-1' size='lg' color='danger' icon='heroicon-m-x-circle'>
                            Inactif
                        </x-filament::badge>
                    @endif
                </div>
            </x-slot>

            <x-slot name="description">
                {{ $mainData->comment }}
            </x-slot>

            <div class="content p-3">
                <ol class="relative text-gray-500 border-s-2 border-gray-200">
                    <li class="border-b p-3 rounded-md mb-10 ms-6 shadow-md">
                        <span
                            class="absolute flex items-center justify-center w-8 h-8 {{ $isDraft ? 'bg-gray-200' : 'bg-emerald-400' }} rounded-full -start-4 ring-4 ring-white">
                            <x-filament::icon icon="heroicon-m-check"
                                class="h-6 w-6 {{ $isDraft ? 'text-gray-500' : 'text-white' }}" />
                        </span>
                        <div class="flex items-center justify-between">
                            <h3 class="text-gray-900 leading-tight">Assignation coursier</h3>
                            @if ($assignmentDate)
                                <div class="flex items-center">
                                    <p class="text-xs mr-2">Date assignation:</p>
                                    <x-filament::badge class='w-fit text-xs' color='gray'>
                                        {{ $assignmentDate->format('d M Y - H:i') }}
                                    </x-filament::badge>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center mt-2">
                            @if ($courser)
                                <x-filament::avatar src="{{ filament()->getUserAvatarUrl($courser) }}" size="md"
                                    class="inline mr-3" />

                                <div class="flex flex-col">
                                    <p class="text-gray-500 text-sm mr-2">{{ $courser->name }}</p>
                                    @if (!$courser->is_active)
                                        <x-filament::badge class='w-fit mb-1' size='lg' color='danger'
                                            icon='heroicon-m-x-circle'>
                                            Inactif
                                        </x-filament::badge>
                                    @endif

                                    <div class="flex items-center">
                                        <div class="flex items-center">
                                            <x-filament::icon icon='heroicon-o-phone'
                                                class='h-4 w-4 text-gray-500 mr-1' />
                                            <p class="text-gray-500 text-sm">
                                                {{ $courser->phone }}
                                            </p>
                                        </div>

                                        <span class="w-1 h-1 rounded-full bg-gray-500 mx-3"></span>

                                        <div class="flex items-center">
                                            <x-filament::icon icon='heroicon-o-envelope'
                                                class='h-4 w-4 text-gray-500 mr-1' />
                                            <p class="text-gray-500 text-sm">
                                                {{ $courser->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </li>

                    <li class="border-b p-3 rounded-md mb-10 ms-6 shadow-md">
                        <span
                            class="absolute flex items-center justify-center w-8 h-8 {{ $isAwaitingPickup ? 'bg-gray-200' : ($deliveryStatus->getRank() > 2 ? 'bg-emerald-400' : 'bg-gray-200') }} rounded-full -start-4 ring-4 ring-white">
                            @if ($isAwaitingPickup)
                                <x-filament::icon icon="heroicon-m-check" class="h-6 w-6 text-amber-500" />
                            @elseif($deliveryStatus->getRank() > 2)
                                <x-filament::icon icon="heroicon-m-check" class="h-6 w-6 text-white" />
                            @else
                                <x-filament::icon icon="heroicon-m-check" class="h-6 w-6 text-gray-500" />
                            @endif
                        </span>
                        <div class="flex items-center justify-between">
                            <h3 class="text-gray-900 leading-tight">Levée</h3>
                            @if ($isAwaitingPickup)
                                <div class="flex items-center">
                                    <p class="text-xs mr-2">Temps restant:</p>
                                    <x-filament::badge class='w-fit text-xs' color='{{ $color }}'>
                                        {{ $formattedRemainingTime }}
                                    </x-filament::badge>
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex w-full">
                                @if ($isAwaitingPickup)
                                    <div class="flex flex-col">
                                        <p class="text-sm">Attente récupération par coursier...</p>
                                        <p class="text-xs">Cliquez sur le bouton
                                            <b class="text-yellow-400">Date levée</b>
                                            pour définir la date de levée
                                        </p>
                                    </div>
                                @endif

                                @if ($isDraft)
                                    <p class="text-sm">Récupération courrier par coursier</p>
                                @endif

                                @if ($pickupDate)
                                    <div class="flex items-center">
                                        <p class="text-sm mr-2">Courrier récupéré par coursier:</p>
                                        <x-filament::badge class='w-fit text-xs col-span-1' color='primary'>
                                            {{ $pickupDate?->format('d M Y - H:i') }}
                                        </x-filament::badge>
                                    </div>
                                @endif
                            </div>

                            @if ($isAwaitingPickup && !$pickupDate)
                                <div class="action-container w-full flex justify-end">
                                    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
                                        {{ $getAction('setPickupDate') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </li>

                    @if ($isCancelled)
                        <li class="border-b p-3 rounded-md mb-10 ms-6 shadow-md">
                            <span
                                class="absolute flex items-center justify-center w-8 h-8 bg-red-400 rounded-full -start-4 ring-4 ring-white">
                                <x-filament::icon icon="heroicon-m-x-mark" class="h-6 w-6 text-white" />
                            </span>
                            <h3 class="text-gray-900 leading-tight">
                                Courrier annulé
                            </h3>
                        </li>
                    @endif

                    <li class="border-b p-3 rounded-md mb-10 ms-6 shadow-md">
                        <span
                            class="absolute flex items-center justify-center w-8 h-8 {{ $isNotDelivered ? 'bg-red-400' : ($isAwaitingDelivery ? 'bg-gray-200' : ($isDelivered || $isRejected ? 'bg-emerald-400' : 'bg-gray-200')) }} rounded-full -start-4 ring-4 ring-white">

                            @if ($isNotDelivered)
                                <x-filament::icon icon="heroicon-m-x-mark" class="h-6 w-6 text-white" />
                            @elseif ($isAwaitingDelivery)
                                <x-filament::icon icon="heroicon-m-check" class="h-6 w-6 text-amber-500" />
                            @elseif($isDelivered || $isRejected)
                                <x-filament::icon icon="heroicon-m-check" class="h-6 w-6 text-white" />
                            @else
                                <x-filament::icon icon="heroicon-m-check" class="h-6 w-6 text-gray-500" />
                            @endif
                        </span>
                        <h3 class="text-gray-900 leading-tight">
                            {{ $isNotDelivered ? 'Courier non-distribué' : 'Distribution' }}
                        </h3>
                        <div class="flex items-center justify-between">
                            @if ($isNotDelivered && $rejectionMotive)
                                <x-filament::fieldset class="mt-3 !py-1 border-red-400">
                                    <x-slot name="label">
                                        Motif de non-distribution
                                    </x-slot>

                                    <p class="text-sm">{{ $rejectionMotive }}</p>
                                </x-filament::fieldset>
                            @elseif ($isAwaitingDelivery)
                                <p class="text-sm">Courrier en cours de distribution...</p>
                                <div class="action-container flex justify-end">
                                    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
                                        {{ $getAction('courierNotDelivered') }}
                                    </div>
                                </div>
                            @elseif ($isDelivered)
                                <p class="text-sm">Courrier arrivé chez {{ strtolower($getLabel()) }}</p>
                            @else
                                <p class="text-sm">Distribution du courrier
                                    @if ($getLabel() === 'Destinataire')
                                        au destinataire
                                    @else
                                        à l'ampliataire
                                    @endif
                                </p>
                            @endif
                        </div>
                    </li>

                    <li class="border-b p-3 shadow-md rounded-md ms-6">
                        <span
                            class="absolute flex items-center justify-center w-8 h-8 {{ $isDelivered ? 'bg-emerald-400' : ($isRejected || $isNotDelivered ? 'bg-red-400' : 'bg-gray-200') }} rounded-full -start-4 ring-4 ring-white">

                            @if ($isDelivered)
                                <x-filament::icon icon='heroicon-m-map-pin' class='h-6 w-6 text-white' />
                            @elseif($isRejected || $isNotDelivered)
                                <x-filament::icon icon='heroicon-m-map-pin' class='h-6 w-6 text-white' />
                            @else
                                <x-filament::icon icon='heroicon-m-map-pin' class='h-6 w-6 text-gray-500' />
                            @endif
                        </span>
                        <div class="flex justify-between">
                            <h3 class="text-gray-900 leading-tight">Destinataire</h3>
                            @if ($isRejected)
                                <div class="flex items-center">
                                    <x-filament::badge class='w-fit text-xs col-span-1 mr-2' color='danger'>
                                        Courier rejetté
                                    </x-filament::badge>

                                    @if ($isRejected && !$rejectionMotive)
                                        <div class="action-container flex justify-end">
                                            <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
                                                {{ $getAction('submitRejectionMotive') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                        @if ($depositDate)
                            <div class="flex items-center">
                                <div class="flex items-center w-full">
                                    <p class="text-sm mr-2">Date arrivé chez {{ strtolower($getLabel()) }}:</p>
                                    <x-filament::badge class='w-fit text-xs col-span-1' color='primary'>
                                        {{ $depositDate?->format('d M Y - H:i') }}
                                    </x-filament::badge>
                                </div>

                                @if ($isDelivered)
                                    @if (!$receipt)
                                        <div class="action-container flex justify-end">
                                            <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
                                                {{ $getAction('submitReceipt') }}
                                            </div>
                                        </div>
                                    @else
                                        <x-filament::icon-button icon="heroicon-o-eye" size="md"
                                            label="Voir décharge" tooltip="Voir décharge" href="{{ $receiptPath }}"
                                            tag="a" target="_blank" />
                                    @endif

                                @endif
                            </div>

                            @if ($isDelivered)
                                <div class="mt-1 flex items-center w-full">
                                    <p class="text-sm mr-1">Signature:</p>
                                    <img class="mt-2" src="{{ $signaturePath }}" alt="Signature" style="width: 100px; height: 60px;">
                                    {{-- <a href="{{ $signaturePath }}" target="_blank" title="Voir Signature">
                                        <img class="mt-2" src="{{ $signaturePath }}" alt="Signature" style="width: 100px; height: 60px;">
                                    </a>--}}
                                </div>
                            @endif
                        @endif
                        <div class="flex items-center mt-2">
                            <div class="mr-3 border border-gray-500 rounded-full p-1">
                                <x-filament::icon icon='heroicon-o-building-office' class='h-5 w-5 text-gray-500' />
                            </div>

                            <div class="flex flex-col">
                                <p class="text-gray-500 text-sm">{{ $recipient->name }}</p>

                                <div class="flex items-center flex-wrap">
                                    <div class="flex items-center">
                                        <x-filament::icon icon='heroicon-o-phone'
                                            class='h-4 w-4 text-gray-500 mr-1' />
                                        <p class="text-gray-500 text-sm text-nowrap">
                                            {{ $recipient->phone }}
                                        </p>
                                    </div>

                                    <span class="w-1 h-1 rounded-full bg-gray-500 mx-3"></span>

                                    <div class="flex items-center">
                                        <x-filament::icon icon='heroicon-o-envelope'
                                            class='h-4 w-4 text-gray-500 mr-1' />
                                        <p class="text-gray-500 text-sm">
                                            {{ $recipient->email }}
                                        </p>
                                    </div>

                                    <span class="w-1 h-1 rounded-full bg-gray-500 mx-3"></span>

                                    <div class="flex items-center">
                                        <x-filament::icon icon='heroicon-o-map-pin'
                                            class='h-4 w-4 text-gray-500 mr-1' />
                                        <p class="text-gray-500 text-sm">
                                            {{ $recipient->address }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- CONTACT --}}

                        @if ($contact)
                            <x-filament::fieldset class="flex items-center mt-3 ml-5 !py-1">
                                <x-slot name="label">
                                    Contact
                                </x-slot>

                                <p class="text-gray-500 text-sm">
                                    {{ $contact->name }}
                                </p>

                                <span class="w-1 h-1 rounded-full bg-gray-500 mx-3"></span>


                                <div class="flex items-center">
                                    <x-filament::icon icon='heroicon-o-phone' class='h-4 w-4 text-gray-500 mr-1' />
                                    <p class="text-gray-500 text-sm">
                                        {{ $contact->phone }}
                                    </p>
                                </div>

                                <span class="w-1 h-1 rounded-full bg-gray-500 mx-3"></span>

                                <div class="flex items-center">
                                    <x-filament::icon icon='heroicon-o-envelope' class='h-4 w-4 text-gray-500 mr-1' />
                                    <p class="text-gray-500 text-sm">
                                        {{ $contact->email }}
                                    </p>
                                </div>
                            </x-filament::fieldset>
                        @endif

                        @if ($isRejected && $rejectionMotive)
                            <x-filament::fieldset class="mt-3 ml-5 !py-1 border-red-400">
                                <x-slot name="label">
                                    Motif rejet
                                </x-slot>

                                <p class="text-sm">{{ $rejectionMotive }}</p>
                            </x-filament::fieldset>
                        @endif
                    </li>
                </ol>
            </div>
        </x-filament::section>

    </div>
@endif
