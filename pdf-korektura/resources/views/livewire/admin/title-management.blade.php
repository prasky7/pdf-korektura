<div>
    <h2 class="text-2xl font-bold text-orange-600 mb-6">Správa titulů</h2>

    @if($editingTitleId !== null)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">{{ $editingTitleId ? 'Upravit titul' : 'Nový titul' }}</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Název <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Popis</label>
                    <textarea wire:model="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="h-4 w-4 text-orange-600 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 text-sm text-gray-600">Aktivní</label>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="cancelEdit"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Zrušit</button>
                    <button type="submit"
                            class="bg-orange-600 text-white py-2 px-6 rounded-md hover:bg-orange-700 transition font-medium">Uložit</button>
                </div>
            </form>
        </div>
    @else
        <div class="mb-4">
            <button wire:click="create" class="bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 transition font-medium">
                + Nový titul
            </button>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat tituly..."
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 w-80">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Název <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="name" /></th>
                        <th wire:click="sortBy('description')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Popis <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="description" /></th>
                        <th wire:click="sortBy('is_active')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Stav <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="is_active" /></th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($titles as $title)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $title->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate">{{ $title->description ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($title->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Aktivní</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">Neaktivní</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $title->id }})" class="text-orange-600 hover:text-orange-800">Upravit</button>
                                <button wire:click="delete({{ $title->id }})" wire:confirm="Opravdu chcete smazat tento titul?"
                                        class="text-red-600 hover:text-red-900">Smazat</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Žádné tituly nenalezeny.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $titles->links() }}
        </div>
    </div>
</div>
