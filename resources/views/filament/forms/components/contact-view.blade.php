@php
    $contact = $getRecord();
@endphp

<div class="grid grid-cols-2 border border-gray-50 shadow-md rounded-md p-2">
    <div class="flex flex-col col-span-1">
        <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 text-wrap'>
            {{ $contact->name }}
        </h3>
        <p class='text-gray-500'>{{ $contact->entity }}</p>
        {{-- <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-gray-500 ml-1 mr-2' />
        <p class="text-sm">
            {{ $user->email }}
        </p> --}}
    </div>

    <div class="flex flex-col col-span-1">
        <div class="flex">
            <x-filament::icon icon='heroicon-m-envelope' class='h-5 w-5 text-blue-500 ml-1 mr-2' />
            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 text-wrap'>
                {{ $contact->email }}
            </h3>
        </div>

        <div class="flex">
            <x-filament::icon icon='heroicon-m-phone' class='h-5 w-5 text-blue-500 ml-1 mr-2' />
            <h3 class='flex items-center mb-1 text-sm font-semibold text-gray-900 text-wrap'>
                {{ $contact->phone }}
            </h3>
        </div>
    </div>
</div>
