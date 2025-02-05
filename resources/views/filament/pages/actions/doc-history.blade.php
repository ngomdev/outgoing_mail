@foreach ($docActivities as $date => $activities)
    @php
        $activities = $activities->sortByDesc('created_at');
        $oldData = [];
        $newData = [];

        foreach ($activities as $item) {
            if (isset($item->properties['old'])) {
                $oldData[] = $item->properties['old'];
            }

            if (isset($item->properties['attributes'])) {
                $newData[] = $item->properties['attributes'];
            }
        }

        dump($oldData, $newData);

    @endphp
    <div
        class="px-4 py-2 w-fit mx-auto shadow-md rounded-full bg-white text-gray-600 text-sm font-medium dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sticky top-20">
        {{ $date }}
    </div>

    @foreach ($activities as $item)
        @php
            $causer = App\Models\User::find($item->causer_id);
        @endphp

        <x-filament::section collapsible collapsed>
            <div class="flex justify-between items-center cursor-pointer">
                <x-slot name="heading">
                    <div class="flex items-center gap-x-2">
                        <x-filament::avatar
                            src="https://ui-avatars.com/api/?name=S+A&amp;color=FFFFFF&amp;background=030712"
                            alt="Dan Harrin" />

                        <div class="flex flex-col text-left">
                            <span class="font-semibold dark:text-gray-300">{{ $causer->name }}</span>
                            <span class="text-xs text-gray-700 dark:text-gray-200">
                                {{ $item->created_at->format('H:i:s') }}
                            </span>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="headerEnd">
                    <div class="flex gap-x-2">

                        <span
                            class="py-2 px-4 rounded-full text-xs flex items-center opacity-70 transition group-hover:opacity-100 bg-blue-50/70 dark:bg-blue-100/10 text-blue-700 dark:text-blue-400 dark:border-blue-600">
                            {{ $item->event === 'created' ? __('Document créé') : __('Mise à jour') }} </span>

                        <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->

                        <div
                            class="flex items-center gap-1 p-2 rounded-lg text-xs text-gray-700 bg-gray-100 font-medium opacity-70 transition group-hover:opacity-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <span>{{ __('Document') }}</span>
                            <span>#{{ $item->subject_id }}</span>
                        </div>
                    </div>
                </x-slot>
            </div>


            <div>
                <table
                    class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5 w-full overflow-hidden text-sm !table-fixed">
                    <!--[if BLOCK]><![endif]-->
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 !py-2"
                                width="20%">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->

                                    <span
                                        class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ __('Champ') }}
                                    </span>

                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 !py-2"
                                width="40%">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->

                                    <span
                                        class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ __('Ancienne valeur') }}
                                    </span>

                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                </span>
                            </th>
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 !py-2"
                                width="40%">
                                <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->

                                    <span
                                        class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ __('Nouvelle valeur') }}
                                    </span>

                                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                                </span>
                            </th>
                        </tr>
                    </thead>

                    <!--[if ENDBLOCK]><![endif]-->
                    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                        <!--[if BLOCK]><![endif]-->
                        @foreach ($oldData as $propKey => $property)
                            <tr class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75">
                                <td
                                    class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 px-4 py-2 align-top sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    Nom document
                                </td>

                                <!--[if BLOCK]><![endif]-->
                                <td
                                    class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 px-4 py-2 align-top overflow-x-scroll">
                                    <!--[if BLOCK]><![endif]--> <!--[if BLOCK]><![endif]--> Francis-Glassbbnn
                                    <!--[if ENDBLOCK]><![endif]-->
                                    <!--[if ENDBLOCK]><![endif]-->
                                </td>

                                <td
                                    class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 px-4 py-2 align-top overflow-x-scroll">
                                    <!--[if BLOCK]><![endif]--> <!--[if BLOCK]><![endif]--> Francis-Glas
                                    <!--[if ENDBLOCK]><![endif]-->
                                    <!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <!--[if ENDBLOCK]><![endif]-->
                            </tr>
                        @endforeach

                        @php
                            dump($oldData, $newData);
                        @endphp
                        <!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                    <!--[if BLOCK]><![endif]--> <!--[if ENDBLOCK]><![endif]-->
                </table>
            </div>

        </x-filament::section>
    @endforeach
@endforeach
