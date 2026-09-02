<x-filament::page>
    <div class="space-y-6">
        <x-filament::tabs>
            <x-filament::tabs.item
                :active="$tab === 'items'"
                wire:click="$set('tab', 'items')"
            >
                {{ __('content-studio-plugin::content_studio.items') }}
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$tab === 'editor'"
                wire:click="$set('tab', 'editor')"
            >
                {{ __('content-studio-plugin::content_studio.editor') }}
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$tab === 'revisions'"
                wire:click="$set('tab', 'revisions')"
            >
                {{ __('content-studio-plugin::content_studio.revisions') }}
            </x-filament::tabs.item>
        </x-filament::tabs>

        @if ($tab === 'items')
            {{ $this->table }}
        @endif

        @if ($tab === 'editor')
            @if (! $item)
                <x-filament::section>
                    <div class="text-sm text-gray-600">
                        {{ __('content-studio-plugin::content_studio.select_item_for_editor') }}
                    </div>
                </x-filament::section>
            @else
                {{ $this->form }}
            @endif
        @endif

        @if ($tab === 'revisions')
            @if (! $item)
                <x-filament::section>
                    <div class="text-sm text-gray-600">
                        {{ __('content-studio-plugin::content_studio.select_item_for_revisions') }}
                    </div>
                </x-filament::section>
            @else
                {{ $this->getRevisionsTable() }}
            @endif
        @endif
    </div>
</x-filament::page>
