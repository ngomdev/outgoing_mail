<div class="grid grid-cols-2">
    <div class="flex flex-col col-span-1">
        <x-filament::avatar src="{{ filament()->getUserAvatarUrl($user) }}" class="mr-2" size="lg" />
        <p class="text-sm text-nowrap font-semibold">{{ $user->registration_number }}</p>
    </div>
    <div class="flex flex-col col-span-1">
        <p class="text-sm text-nowrap">{{ $user->name }}</p>
        <x-filament::badge class='w-fit text-xs mt-1' color='gray'>
            {{ $user->getRoleNames()->first() }}
        </x-filament::badge>
        <div class="flex items-center">
            <x-filament::icon icon="heroicon-m-envelope" class="h-5 w-5 text-cyan-500 dark:text-cyan-400 ml-1 mr-2" />
            <p class="text-sm text-nowrap">{{ $user->email }}</p>
        </div>
        <div class="flex items-center">
            <x-filament::icon icon="heroicon-m-phone" class="h-5 w-5 text-cyan-500 dark:text-gray-400 ml-1 mr-2" />
            <p class="text-sm text-nowrap">{{ $user->phone }}</p>
        </div>
    </div>
    {{--
    <div class="flex items-center">
        <x-filament::icon icon="heroicon-m-envelope" class="h-5 w-5 text-cyan-500 dark:text-cyan-400 ml-1 mr-2" />
        <p class="text-sm text-nowrap">{{ $user->email }}</p>
    </div>

    <div class="flex items-center">
        <x-filament::icon icon="heroicon-m-phone" class="h-5 w-5 text-cyan-500 dark:text-gray-400 ml-1 mr-2" />
        <p class="text-sm text-nowrap">{{ $user->phone }}</p>
    </div> --}}
</div>
