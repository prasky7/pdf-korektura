<div>
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-orange-600 hover:text-orange-800 text-sm">&larr; Zpět na dashboard</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-orange-600">{{ $pdfDocument->name }}</h1>
                        <p class="text-gray-500 mt-1">{{ $pdfDocument->title->name }} @if($pdfDocument->issue_title) &middot; {{ $pdfDocument->issue_title }} @endif</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($pdfDocument->status === 'uploaded') bg-blue-100 text-blue-800
                        @elseif($pdfDocument->status === 'in_progress') bg-pink-100 text-pink-800
                        @elseif($pdfDocument->status === 'returned') bg-yellow-100 text-yellow-800
                        @elseif($pdfDocument->status === 'completed') bg-green-100 text-green-800
                        @endif">
                        {{ $pdfDocument->statusLabel() }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                    <div>
                        <p class="text-gray-500">Strana</p>
                        <p class="font-medium">{{ $pdfDocument->page_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Deadline</p>
                        <p class="font-medium {{ $pdfDocument->deadline_date->isPast() && $pdfDocument->status !== 'completed' ? 'text-red-600' : '' }}">
                            {{ $pdfDocument->deadline_date->format('d.m.Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Vložil</p>
                        <p class="font-medium">{{ $pdfDocument->uploadedBy->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Korektor</p>
                        <p class="font-medium">{{ $pdfDocument->assignedTo?->name ?? 'Nepřiřazeno' }}</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('pdf.preview', $pdfDocument) }}" target="_blank"
                       class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 text-sm font-medium">Náhled v prohlížeči</a>
                    <a href="{{ route('pdf.download', $pdfDocument) }}"
                       class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 text-sm font-medium">Stáhnout</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Historie verzí</h3>
                <div class="space-y-4">
                    @foreach($pdfDocument->versions as $version)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium">Verze {{ $version->version_number }}</p>
                                <p class="text-sm text-gray-500">{{ $version->change_summary }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $version->uploadedBy->name }} &middot; {{ $version->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <a href="{{ route('pdf.download', ['pdfDocument' => $pdfDocument, 'version' => $version->version_number]) }}"
                               class="text-orange-600 hover:text-orange-800 text-sm">Stáhnout</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Historie aktivit</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @foreach($pdfDocument->activityLogs as $log)
                        <div class="border-l-2 border-orange-300 pl-4">
                            <p class="text-sm font-medium">{{ $log->actionLabel() }}</p>
                            <p class="text-xs text-gray-500">{{ $log->user?->name ?? 'Systém' }}</p>
                            @if($log->details)
                                <p class="text-sm text-gray-600 mt-1">{{ $log->details }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $log->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
