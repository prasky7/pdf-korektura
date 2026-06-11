<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Správa uživatelů</h2>
        <button wire:click="toggleCreateForm"
                class="bg-slate-800 text-white py-2 px-4 rounded-md hover:bg-slate-700 transition font-medium text-sm">
            @if($showCreateForm)
                Zrušit
            @else
                + Nový uživatel
            @endif
        </button>
    </div>

    {{-- Create User Form --}}
    @if($showCreateForm)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Vytvořit lokálního uživatele</h3>
            <form wire:submit="createUser" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jméno a příjmení <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="newName" required placeholder="Jan Novák"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                        @error('newName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Uživatelské jméno <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="newUsername" required placeholder="jnovak"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                        @error('newUsername') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" wire:model="newEmail" required placeholder="jnovak@example.cz"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    @error('newEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Heslo <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="newPassword" required placeholder="Min. 6 znaků"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                        @error('newPassword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Potvrzení hesla <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="newPasswordConfirmation" required placeholder="Zopakujte heslo"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-4">
                        @foreach($roles as $role)
                            <label class="flex items-center bg-gray-50 px-3 py-2 rounded-md border border-gray-200 cursor-pointer hover:bg-gray-100">
                                <input type="checkbox" wire:model="newRoles" value="{{ $role->name }}"
                                       class="h-4 w-4 text-slate-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 font-medium">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('newRoles') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-green-600 text-white py-2 px-6 rounded-md hover:bg-green-700 transition font-medium">
                        Vytvořit uživatele
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if($editingUserId)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Upravit role</h3>
            <form wire:submit="saveRoles" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}"
                                       class="h-4 w-4 text-slate-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="cancelEdit"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Zrušit</button>
                    <button type="submit"
                            class="bg-slate-800 text-white py-2 px-6 rounded-md hover:bg-slate-700 transition font-medium">Uložit</button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat uživatele..."
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 w-80">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Jméno <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="name" /></th>
                        <th wire:click="sortBy('email')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Email <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="email" /></th>
                        <th wire:click="sortBy('username')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Uživ. jméno <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="username" /></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->username ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs mr-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <button wire:click="editUser({{ $user->id }})" class="text-slate-600 hover:text-slate-900">Upravit role</button>
                                @if($user->id !== auth()->id())
                                    <button wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Opravdu chcete smazat uživatele {{ $user->name }}?"
                                            class="text-red-600 hover:text-red-900">Smazat</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Žádní uživatelé nenalezeni.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
