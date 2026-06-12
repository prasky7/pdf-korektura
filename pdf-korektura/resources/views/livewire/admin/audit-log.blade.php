<div>
    <h2 class="text-2xl font-bold text-orange-600 mb-6">Audit log</h2>

    <div class="bg-white rounded-lg shadow mb-6">
        {{-- Filters --}}
        <div class="p-4 border-b border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Akce</label>
                    <select wire:model.live="actionFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                        <option value="">Všechny akce</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Uživatel</label>
                    <select wire:model.live="userFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                        <option value="">Všichni uživatelé</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Titul</label>
                    <select wire:model.live="titleFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                        <option value="">Všechny tituly</option>
                        @foreach($titles as $title)
                            <option value="{{ $title->id }}">{{ $title->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Název PDF</label>
                    <input wire:model.live.debounce.300ms="pdfNameSearch" type="text" placeholder="Hledat název PDF..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Datum akce od</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Datum akce do</label>
                    <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Deadline od</label>
                    <input type="date" wire:model.live="deadlineFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Deadline do</label>
                    <input type="date" wire:model.live="deadlineTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                </div>
            </div>
            <div class="mt-3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat v detailech..."
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm w-64">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('created_at')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Čas akce <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="created_at" /></th>
                        <th wire:click="sortBy('user_id')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Uživatel <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="user_id" /></th>
                        <th wire:click="sortBy('action')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Akce <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="action" /></th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Název PDF</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Originální soubor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titul</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detaily</th>
                        <th wire:click="sortBy('ip_address')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">IP <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="ip_address" /></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->user?->name ?? 'Systém' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs">{{ $log->actionLabel() }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->pdfDocument)
                                    <a href="{{ route('pdf.detail', $log->pdfDocument) }}" class="text-orange-600 hover:underline">{{ $log->pdfDocument->name }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 font-mono" title="{{ $log->pdfVersion?->file_path ?? '' }}">
                                @if($log->pdfVersion)
                                    {{ $log->pdfVersion->original_filename ?? basename($log->pdfVersion->file_path) }}
                                    <div class="mt-1 space-x-2">
                                        <a href="{{ route('pdf.preview.silent', $log->pdfDocument) }}" target="_blank"
                                           class="text-orange-500 hover:text-orange-700 text-xs">Náhled</a>
                                        <a href="{{ route('pdf.download.silent', ['pdfDocument' => $log->pdfDocument, 'version' => $log->pdfVersion->version_number]) }}"
                                           class="text-orange-500 hover:text-orange-700 text-xs">Stáhnout</a>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->pdfDocument?->title?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->pdfDocument && $log->pdfDocument->deadline_date)
                                    <span class="{{ $log->pdfDocument->deadline_date->isPast() && $log->pdfDocument->status !== 'completed' ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $log->pdfDocument->deadline_date->format('d.m.Y H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 max-w-md truncate">{{ $log->details ?? '-' }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">Žádné záznamy v logu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>
