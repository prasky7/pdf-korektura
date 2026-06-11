<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Archiv</h2>
        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
            Celkem archivovaných: {{ $totalArchived }}
        </span>
    </div>

    <div class="bg-white rounded-lg shadow mb-6">
        {{-- Filters --}}
        <div class="p-4 border-b border-gray-200 flex flex-wrap gap-4 items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat..."
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 w-64">
            <select wire:model.live="titleFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                <option value="">Všechny tituly</option>
                @foreach($titles as $title)
                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Název <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="name" /></th>
                        <th wire:click="sortBy('title_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Titul <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="title_id" /></th>
                        <th wire:click="sortBy('issue_title')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Vydání <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="issue_title" /></th>
                        <th wire:click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Stav <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="status" /></th>
                        <th wire:click="sortBy('current_version_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Verze <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="current_version_number" /></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vložil</th>
                        <th wire:click="sortBy('archived_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Archivováno <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="archived_at" /></th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pdfs as $pdf)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pdf->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->title->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->issue_title ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($pdf->status === 'uploaded') bg-gray-100 text-gray-800
                                    @elseif($pdf->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($pdf->status === 'returned') bg-yellow-100 text-yellow-800
                                    @elseif($pdf->status === 'completed') bg-green-100 text-green-800
                                    @endif">
                                    {{ $pdf->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">v{{ $pdf->current_version_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->uploadedBy?->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->archived_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a href="{{ route('pdf.download', $pdf) }}" class="text-slate-600 hover:text-slate-900">Stáhnout</a>
                                <button wire:click="unarchive({{ $pdf->id }})"
                                        class="text-blue-600 hover:text-blue-900">Obnovit</button>
                                <button wire:click="deleteArchived({{ $pdf->id }})"
                                        wire:confirm="Opravdu chcete smazat toto archivované PDF včetně všech verzí?"
                                        class="text-red-600 hover:text-red-900">Smazat</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">Archiv je prázdný.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $pdfs->links() }}
        </div>
    </div>
</div>
