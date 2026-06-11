<div>
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Moje přiřazená PDF</h2>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uploadingForPdfId): ?>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Nahrát opravené PDF</h3>
            <form wire:submit="submitCorrection" class="space-y-4">
                
                <div x-data="{
                        dragging: false,
                        fileName: null,
                        handleDrop(e) {
                            const files = e.dataTransfer.files;
                            if (files.length > 0) {
                                const file = files[0];
                                if (file.type === 'application/pdf') {
                                    this.fileName = file.name;
                                    $wire.upload('correctedPdf', file,
                                        (uploadedFilename) => { },
                                        () => { this.fileName = null; alert('Chyba pøi nahrávání souboru.'); },
                                        (event) => { }
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
                    x-on:reset-correction-dropzone.window="fileName = null"
                >
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opravené PDF</label>

                    <div :class="dragging ? 'border-slate-500 bg-slate-50' : 'border-gray-300 bg-gray-50'"
                         class="relative border-2 border-dashed rounded-lg p-6 text-center transition-colors cursor-pointer hover:border-slate-400 hover:bg-slate-50">

                        <input type="file"
                               wire:model="correctedPdf"
                               accept=".pdf"
                               x-on:change="if ($event.target.files.length > 0) { fileName = $event.target.files[0].name }"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                        <div class="flex flex-col items-center gap-2 pointer-events-none">
                            <template x-if="!fileName">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-semibold text-slate-700">Pøetáhnìte PDF sem</span>
                                            <span class="text-gray-400 mx-1">nebo</span>
                                            <span class="font-semibold text-slate-700 underline">kliknìte pro výbìr</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">Pouze PDF soubory (max 50 MB)</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="fileName">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700" x-text="fileName"></p>
                                    <p class="text-xs text-gray-400">Kliknìte nebo pøetáhnìte jiný soubor pro nahrazení</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div wire:loading wire:target="correctedPdf" class="flex items-center gap-2 mt-2">
                        <svg class="animate-spin h-4 w-4 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="text-sm text-gray-500">Nahrávání souboru...</span>
                    </div>

                    <div wire:loading.remove wire:target="correctedPdf">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['correctedPdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Popis změn</label>
                    <textarea wire:model="changeSummary" rows="3" placeholder="Stručný popis provedených změn..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['changeSummary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="returnForRevision" id="returnForRevision" class="h-4 w-4 text-slate-600 border-gray-300 rounded">
                    <label for="returnForRevision" class="ml-2 text-sm text-gray-600">Vrátit k další úpravě (ne archivovat)</label>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="cancelUpload"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Zrušit</button>
                    <button type="submit" wire:loading.attr="disabled"
                            class="bg-slate-800 text-white py-2 px-6 rounded-md hover:bg-slate-700 transition font-medium disabled:opacity-50">
                        <span wire:loading.remove>Nahrát opravu</span>
                        <span wire:loading>Ukládání...</span>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Verze</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pdfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pdf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($pdf->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->title->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="<?php echo e($pdf->deadline_date->isPast() ? 'text-red-600 font-semibold' : ''); ?>">
                                    <?php echo e($pdf->deadline_date->format('d.m.Y H:i')); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">v<?php echo e($pdf->current_version_number); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="<?php echo e(route('pdf.preview', $pdf)); ?>" target="_blank" class="text-slate-600 hover:text-slate-900">Náhled</a>
                                <a href="<?php echo e(route('pdf.download', $pdf)); ?>" class="text-slate-600 hover:text-slate-900">Stáhnout</a>
                                <button wire:click="startUpload(<?php echo e($pdf->id); ?>)" class="text-green-600 hover:text-green-900">Nahrát opravu</button>
                                <button wire:click="releasePdf(<?php echo e($pdf->id); ?>)" class="text-red-600 hover:text-red-900">Uvolnit</button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Nemáte žádná přiřazená PDF.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            <?php echo e($pdfs->links()); ?>

        </div>
    </div>
</div>
<?php /**PATH E:\Dev\TEST01\pdf-korektura\resources\views/livewire/my-assignments.blade.php ENDPATH**/ ?>