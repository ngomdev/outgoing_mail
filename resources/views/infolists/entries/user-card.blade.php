<div
    class="w-full py-4 px-2 max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col items-center">
        <x-filament::avatar src="{{ filament()->getUserAvatarUrl($user) }}" class="h-10 w-10" />
        <h5 class="mb-1 text-md font-medium text-gray-900 dark:text-white">{{ $user->name }}</h5>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ '@' . $user->getRoleNames()->first() }}</span>
    </div>
</div>
