@php
    if ($getRecord() instanceof App\Models\Document) {
        $doc = $getRecord();
        if ($type === 'initiator') {
            if ($doc->initiatorUser) {
                $name = $doc->initiatorUser->name;
                $email = $doc->initiatorUser->email;
                $phone = $doc->initiatorUser->phone;
                $isActive = $doc->initiatorUser->is_active;
                $avatarUrl = filament()->getUserAvatarUrl($doc->initiatorUser);
            } elseif ($doc->externalInitiator) {
                $name = $doc->externalInitiator->name;
                $email = $doc->externalInitiator->email;
                $phone = $doc->externalInitiator->phone;
                $isActive = $doc->externalInitiator->is_active;
                $avatarUrl = $doc->externalInitiator->logo_url
                    ? asset("storage/{$doc->externalInitiator->logo_url}")
                    : "https://ui-avatars.com/api/?name=$name&color=FFFFFF&background=030712";
            }
        } elseif ($type === 'demandeur') {
            $name = $doc->createdBy->name;
            $email = $doc->createdBy->email;
            $phone = $doc->createdBy->phone;
            $isActive = $doc->createdBy->is_active;
            $avatarUrl = filament()->getUserAvatarUrl($doc->createdBy);
        }
    } elseif ($getRecord() instanceof App\Models\Team) {
        $team = $getRecord();
        $name = $team->manager->name;
        $email = $team->manager->email;
        $phone = $team->manager->phone;
        $isActive = $team->manager->is_active;
        $avatarUrl = filament()->getUserAvatarUrl($team->manager);
    }
@endphp

<div class='flex flex-col shadow-md rounded-md p-2'>
    <div class="flex mb-2">
        <p class="font-semibold underline mr-2">{{ $getLabel() }}</p>
        @if (!$isActive)
            <x-filament::badge class='w-fit' size='lg' color='danger' icon='heroicon-m-x-circle'>
                Inactif
            </x-filament::badge>
        @endif
    </div>

    <div class='flex items-center my-1 min-w-0'>
        <x-filament::avatar src='{{ $avatarUrl }}' alt='{{ $name }}' class='mr-2' />
        <p class="text-sm break-all mr-2">{{ $name }}</p>
    </div>

    <div class='flex items-center my-1 min-w-0 border-b-2 pb-2'>
        <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-cyan-500 ml-1 mr-2' />
        <p class="text-sm break-all">{{ $email }}</p>
    </div>

    <div class='flex items-center my-1 min-w-0'>
        <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-cyan-500 ml-1 mr-2' />
        <p class="text-sm break-all">{{ $phone ?? '-' }}</p>
    </div>
</div>

{{-- <x-filament::fieldset class="shadow-md">
    <x-slot name="label">
        {{ $getLabel() }}
    </x-slot>

    <div class='flex flex-col'>
        <div class='flex items-center my-1 min-w-0'>
            <x-filament::avatar src='{{ $avatarUrl }}' alt='{{ $name }}' class='mr-2' />
            <p class="text-sm break-words">{{ $name }}</p>
        </div>

        <div class='flex items-center my-1 min-w-0'>
            <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-cyan-500 ml-1 mr-2' />
            <p class="text-sm break-words !truncate">{{ $email }}</p>
        </div>

        <div class='flex items-center my-1 min-w-0 '>
            <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-cyan-500 ml-1 mr-2' />
            <p class="text-sm break-words">{{ $phone ?? '-' }}</p>
        </div>
    </div>

    @if ($type === 'demandeur' && $doc->status !== App\Enums\DocStatus::DRAFT)
        <p class='text-sm italic underline underline-offset-4'>
            Créé le {{ $doc->doc_created_at->format('d M Y - H:i') }}
        </p>
    @endif
</x-filament::fieldset> --}}
