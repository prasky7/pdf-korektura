<div>
    <h2 class="text-2xl font-bold text-orange-600 mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <p class="text-sm text-gray-500 uppercase">Celkem PDF</p>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-400">
            <p class="text-sm text-gray-500 uppercase">Vloženo</p>
            <p class="text-3xl font-bold text-gray-600">{{ $stats['uploaded'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-500">
            <p class="text-sm text-gray-500 uppercase">V procesu</p>
            <p class="text-3xl font-bold text-pink-600">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 uppercase">Hotovo</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 flex flex-wrap gap-4 items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat..."
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 w-64">
            <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Všechny stavy</option>
                <option value="uploaded">Vloženo</option>
                <option value="in_progress">V procesu</option>
                <option value="returned">Vráceno zpět</option>
                <option value="completed">Hotovo</option>
            </select>
            <select wire:model.live="titleFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
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
                        <th wire:click="sortBy('deadline_date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Deadline <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="deadline_date" /></th>
                        <th wire:click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Stav <x-sort-arrow :sortField="$sortField" :sortDirection="$sortDirection" field="status" /></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Korektor</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pdfs as $pdf)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('pdf.detail', $pdf) }}" class="text-orange-700 hover:text-orange-900 hover:underline">{{ $pdf->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->title->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->page_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="{{ $pdf->deadline_date->isPast() && $pdf->status !== 'completed' ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $pdf->deadline_date->format('d.m.Y H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($pdf->status === 'uploaded') bg-blue-100 text-blue-800
                                    @elseif($pdf->status === 'in_progress') bg-pink-100 text-pink-800
                                    @elseif($pdf->status === 'returned') bg-yellow-100 text-yellow-800
                                    @elseif($pdf->status === 'completed') bg-green-100 text-green-800
                                    @endif">
                                    {{ $pdf->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pdf->assignedTo?->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('pdf.detail', $pdf) }}" class="text-orange-600 hover:text-orange-800 mr-3">Detail</a>
                                <a href="{{ route('pdf.download', $pdf) }}" class="text-orange-600 hover:text-orange-800 mr-3">Stáhnout</a>
                                @if($pdf->status === 'completed')
                                    <button wire:click="archivePdf({{ $pdf->id }})" class="text-green-600 hover:text-green-900">Archivovat</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">Žádná PDF k zobrazení.</td>
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
