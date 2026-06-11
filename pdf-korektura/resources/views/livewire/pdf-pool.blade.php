<div>
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Nepřiřazené PDF ke korekci</h2>

    <div class="bg-white rounded-lg shadow mb-6">
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

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Název <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="name" /></th>
                        <th wire:click="sortBy('title_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Titul <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="title_id" /></th>
                        <th wire:click="sortBy('page_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Strana <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="page_number" /></th>
                        <th wire:click="sortBy('issue_title')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Vydání <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="issue_title" /></th>
                        <th wire:click="sortBy('deadline_date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Deadline <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="deadline_date" /></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vložil</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pdfs as $pdf)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pdf->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->title->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->page_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->issue_title ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="{{ $pdf->deadline_date->isPast() ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $pdf->deadline_date->format('d.m.Y H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->uploadedBy->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('pdf.preview', $pdf) }}" target="_blank" class="text-slate-600 hover:text-slate-900 mr-3">Náhled</a>
                                <button wire:click="assignToMe({{ $pdf->id }})" wire:loading.attr="disabled"
                                        class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm disabled:opacity-50">
                                    Přiřadit si
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">Momentálně nejsou žádná volná PDF ke korekci.</td>
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
