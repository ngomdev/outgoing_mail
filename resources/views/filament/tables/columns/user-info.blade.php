@php
    if ($getRecord() instanceof App\Models\User) {
        $user = $getRecord();
    } elseif ($getRecord() instanceof App\Models\Team) {
        $user = $getRecord()->manager;
    } elseif ($getRecord() instanceof App\Models\TeamUser) {
        $user = $getRecord()->user;
    }
@endphp

@if ($user)
    <div class="section flex items-center">
        {{-- <x-filament::avatar src="{{ filament()->getUserAvatarUrl($user) }}" size="md" class="inline mr-2" /> --}}
        <div class="flex flex-col">
            <p class="text-sm text-wrap">{{ $user->name }}</p>
            @if ($user->userFunction)
                <x-filament::badge class='w-fit text-sm' color='secondary'>
                    {{ $user->userFunction->name }}
                </x-filament::badge>
            @endif
            @if (!$user->is_active)
                <x-filament::badge class='w-fit text-sm mt-1 mb-1' size='lg' color='danger' icon='heroicon-m-x-circle'>
                    Inactif
                </x-filament::badge>
            @endif
        </div>
    </div>
@endif
