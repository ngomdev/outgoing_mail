@php
    $user = $getRecord()?->user;
    $role = $getRecord()?->role->name;
    $signed = $getRecord()?->document->status === App\Enums\DocStatus::SIGNED;
@endphp

<div
    class="section grid grid-cols-3 items-center border {{ $signed ? 'border-emerald-400' : 'border-gray-200' }} rounded-xl p-2 shadow-md">
    <div class="flex col-span-1">
        <x-filament::avatar src="{{ filament()->getUserAvatarUrl($user) }}" size="lg" class="inline mr-2" />
        <div class="flex flex-col">
            <p class="text-sm text-nowrap font-semibold">#{{ $user->registration_number }}</p>
            <p class="text-sm text-nowrap">{{ $user->name }}</p>
            <x-filament::badge class="w-fit">
                {{ $user->getRoleNames()->first() }}
            </x-filament::badge>
        </div>
    </div>

    <div class="flex flex-col col-span-1">
        <div class="flex mb-1">
            <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-gray-500 dark:text-gray-400 ml-1 mr-2' />
            <p class="text-sm">
                {{ $user->email }}
            </p>
        </div>

        <div class="flex mb-1">
            <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-gray-500 dark:text-gray-400 ml-1 mr-2' />
            <p class="text-sm">
                {{ $user->phone }}
            </p>
        </div>
    </div>

    <div class="flex flex-col col-span-1">
        <x-filament::badge color="secondary" class="w-fit">
            {{ $role }}
        </x-filament::badge>
    </div>
    @if (!$user->is_active)
        <div class="flex flex-col col-span-1">
            <x-filament::badge class='w-fit' size='lg' color='danger' icon='heroicon-m-x-circle'>
                Inactif
            </x-filament::badge>
        </div>
    @endif

    {{-- <div class="col-span-1 flex justify-center">
        <x-filament::icon-button icon="heroicon-m-eye" href="{{ App\Filament\Resources\SecurityModule\UserResource::getUrl('view', ['record' => $user]) }}" tag="a" label="User" />
    </div> --}}
</div>
