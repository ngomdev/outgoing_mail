<x-filament-panels::page.simple>

    <x-filament-panels::form wire:submit="resetPassword">
        {{ $this->form }}

        <div style="margin: .5rem 0"></div>

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />

    </x-filament-panels::form>

</x-filament-panels::page.simple>
