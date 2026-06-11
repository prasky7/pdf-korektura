<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Archiv</h2>
        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
            Celkem archivovaných: <?php echo e($totalArchived); ?>

        </span>
    </div>

    <div class="bg-white rounded-lg shadow mb-6">
        
        <div class="p-4 border-b border-gray-200 flex flex-wrap gap-4 items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat..."
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 w-64">
            <select wire:model.live="titleFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                <option value="">Všechny tituly</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $titles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($title->id); ?>"><?php echo e($title->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
        </div>

        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vydání</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Verze</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vložil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Archivováno</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pdfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pdf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($pdf->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->title->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->issue_title ?? '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php if($pdf->status === 'uploaded'): ?> bg-gray-100 text-gray-800
                                    <?php elseif($pdf->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($pdf->status === 'returned'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($pdf->status === 'completed'): ?> bg-green-100 text-green-800
                                    <?php endif; ?>">
                                    <?php echo e($pdf->statusLabel()); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">v<?php echo e($pdf->current_version_number); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->uploadedBy?->name ?? '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->archived_at->format('d.m.Y H:i')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a href="<?php echo e(route('pdf.download', $pdf)); ?>" class="text-slate-600 hover:text-slate-900">Stáhnout</a>
                                <button wire:click="unarchive(<?php echo e($pdf->id); ?>)"
                                        class="text-blue-600 hover:text-blue-900">Obnovit</button>
                                <button wire:click="deleteArchived(<?php echo e($pdf->id); ?>)"
                                        wire:confirm="Opravdu chcete smazat toto archivované PDF včetně všech verzí?"
                                        class="text-red-600 hover:text-red-900">Smazat</button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">Archiv je prázdný.</td>
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
<?php /**PATH E:\Dev\TEST01\pdf-korektura\resources\views/livewire/admin/archive.blade.php ENDPATH**/ ?>