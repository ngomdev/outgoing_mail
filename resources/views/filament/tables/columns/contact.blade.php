@php
    if ($getRecord() instanceof App\Models\User) {
        $email = $getRecord()->email;
        $phone = $getRecord()->phone;
    } elseif ($getRecord() instanceof App\Models\Team) {
        $email = $getRecord()->manager->email;
        $phone = $getRecord()->manager->phone;
    } elseif ($getRecord() instanceof App\Models\TeamUser) {
        $email = $getRecord()->user->email;
        $phone = $getRecord()->user->phone;
    } elseif ($getRecord() instanceof App\Models\Recipient) {
        $email = $getRecord()->email;
        $phone = $getRecord()->phone;
    }
@endphp

<div class="flex flex-col">
    <div class='flex items-center text-sm mb-1'>
        <x-filament::icon icon='heroicon-m-envelope' class='h-4 w-4 text-cyan-600 dark:text-cyan-500 ml-1 mr-2' />
        <p class="break-all">{{ $email }}</p>
    </div>

    @if ($phone)
        <div class='flex items-center text-sm'>
            <x-filament::icon icon='heroicon-m-phone' class='h-4 w-4 text-cyan-600 dark:text-cyan-500 ml-1 mr-2' />
            {{ $phone }}
        </div>
    @endif

</div>
