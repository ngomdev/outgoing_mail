<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="w-full p-4 bg-white sm:p-8 dark:bg-gray-800 rounded-2xl">
        {{-- <div class="flex items-center justify-between mb-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">{{ $getLabel() }}</h5>
        </div> --}}
        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($users as $item)
                    @php
                        $user = $item->user;
                        $latestValidation = $user
                            ->docValidationHistory()
                            ->where([['document_id', $getRecord()->id], ['is_active', true]])
                            ->latest()
                            ->first();
                    @endphp
                    <li class="p-2 m-2 rounded-3xl shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <x-filament::avatar class="w-8 h-8 mr-2"
                                    src="{{ filament()->getUserAvatarUrl($user) }}" />
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $user->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    {{ $user->email }}
                                </p>
                            </div>
                            <div
                                class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white ">
                                <x-filament::icon icon="heroicon-m-check-circle"
                                    class="h-5 w-5 {{ $latestValidation ? 'text-emerald-500' : 'text-amber-500' }}" />
                            </div>
                        </div>
                    </li>
                @empty
                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                        {{ __('Aucune entrée') }}
                    </p>
                @endforelse
            </ul>
        </div>
    </div>



    {{-- <ol class="relative text-gray-500 border-s border-gray-200 dark:border-gray-700 dark:text-gray-400">
        @forelse ($getRecord()->parapheurs as $item)
            @php
                $user = $item->user;
                $latestValidation = $user
                    ->docValidationHistory()
                    ->where([['document_id', $getRecord()->id], ['is_active', true]])
                    ->latest()
                    ->first();
            @endphp
            <li class="mb-10 ms-6">
                <span
                    class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-green-900">
                    <svg class="w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5.917 5.724 10.5 15 1.5" />
                    </svg>
                </span>
                <h3 class="font-medium leading-tight">Personal Info</h3>
                <p class="text-sm">Step details here</p>
            </li>
        @empty
            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                {{ __('Aucune entrée') }}
            </p>
        @endforelse
    </ol> --}}

</x-dynamic-component>
