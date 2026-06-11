<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Nahrát nové PDF</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <form wire:submit="save" class="space-y-6">
            {{-- Drag & Drop File Upload --}}
            <div x-data="{
                    dragging: false,
                    fileName: null,
                    handleDrop(e) {
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            const file = files[0];
                            if (file.type === 'application/pdf') {
                                this.fileName = file.name;
                                $wire.set('originalPdfFileName', file.name);
                                $wire.upload('pdfFile', file,
                                    (uploadedFilename) => { /* success – Livewire property updated */ },
                                    () => { /* error */ this.fileName = null; alert('Chyba pøi nahrávání souboru.'); },
                                    (event) => { /* progress */ }
                                );
                            } else {
                                alert('Pouze PDF soubory jsou povoleny.');
                            }
                        }
                    }
                }"
                x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="dragging = false; handleDrop($event)"
                x-on:reset-dropzone.window="fileName = null"
            >
                <label class="block text-sm font-medium text-gray-700 mb-1">PDF soubor</label>

                {{-- Drop Zone --}}
                <div :class="dragging ? 'border-slate-500 bg-slate-50' : 'border-gray-300 bg-gray-50'"
                     class="relative border-2 border-dashed rounded-lg p-8 text-center transition-colors cursor-pointer hover:border-slate-400 hover:bg-slate-50">

                    {{-- Hidden file input --}}
                    <input type="file"
                           wire:model="pdfFile"
                           accept=".pdf"
                           x-on:change="if ($event.target.files.length > 0) { fileName = $event.target.files[0].name }"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    {{-- Upload icon --}}
                    <div class="flex flex-col items-center gap-3 pointer-events-none">
                        <template x-if="!fileName">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <div>
                                    <p class="text-base text-gray-600">
                                        <span class="font-semibold text-slate-700">Přetáhněte PDF sem</span>
                                        <span class="text-gray-400 mx-1">nebo</span>
                                        <span class="font-semibold text-slate-700 underline">klikněte pro výběr</span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">Pouze PDF soubory (max 50 MB)</p>
                                </div>
                            </div>
                        </template>

                        <template x-if="fileName">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-700" x-text="fileName"></p>
                                <p class="text-xs text-gray-400">Klikněte nebo přetáhněte jiný soubor pro nahrazení</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Upload progress --}}
                <div wire:loading wire:target="pdfFile" class="flex items-center gap-2 mt-2">
                    <svg class="animate-spin h-4 w-4 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm text-gray-500">Nahrávání souboru...</span>
                </div>

                <div wire:loading.remove wire:target="pdfFile">
                    @error('pdfFile') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titul <span class="text-red-500">*</span></label>
                <select wire:model="title_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    <option value="">Vyberte titul</option>
                    @foreach($titles as $title)
                        <option value="{{ $title->id }}">{{ $title->name }}</option>
                    @endforeach
                </select>
                @error('title_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Název</label>
                <input type="text" wire:model="name" placeholder="Název PDF"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Číslo strany</label>
                    <input type="number" wire:model="page_number" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    @error('page_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Název vydání</label>
                    <input type="text" wire:model="issue_title" placeholder="např. Číslo 24/2024"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    @error('issue_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uzávěrka – datum <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="deadline_date" required lang="cs-CZ"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    @error('deadline_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uzávěrka – čas <span class="text-red-500">*</span></label>
                    <input type="time" wire:model="deadline_time" required lang="cs-CZ"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    @error('deadline_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Přiřadit korektorovi</label>
                <select wire:model="assigned_to_user_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                    <option value="">Nepřiřazovat konkrétnímu korektorovi</option>
                    @foreach($proofreaders as $proofreader)
                        <option value="{{ $proofreader->id }}">{{ $proofreader->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to_user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled"
                        class="bg-slate-800 text-white py-2 px-6 rounded-md hover:bg-slate-700 transition font-medium disabled:opacity-50">
                    <span wire:loading.remove>Nahrát PDF</span>
                    <span wire:loading>Ukládání...</span>
                </button>
            </div>
        </form>
    </div>
</div>
