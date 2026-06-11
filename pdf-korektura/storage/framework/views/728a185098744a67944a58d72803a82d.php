<div>
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-slate-500">
            <p class="text-sm text-gray-500 uppercase">Celkem PDF</p>
            <p class="text-3xl font-bold text-slate-800"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-400">
            <p class="text-sm text-gray-500 uppercase">Vloženo</p>
            <p class="text-3xl font-bold text-gray-600"><?php echo e($stats['uploaded']); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 uppercase">V procesu</p>
            <p class="text-3xl font-bold text-blue-600"><?php echo e($stats['in_progress']); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 uppercase">Hotovo</p>
            <p class="text-3xl font-bold text-green-600"><?php echo e($stats['completed']); ?></p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 flex flex-wrap gap-4 items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat..."
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 w-64">
            <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500">
                <option value="">Všechny stavy</option>
                <option value="uploaded">Vloženo</option>
                <option value="in_progress">V procesu</option>
                <option value="returned">Vráceno zpět</option>
                <option value="completed">Hotovo</option>
            </select>
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
                        <th wire:click="sortBy('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Název</th>
                        <th wire:click="sortBy('title_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Titul</th>
                        <th wire:click="sortBy('page_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Strana</th>
                        <th wire:click="sortBy('deadline_date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Deadline</th>
                        <th wire:click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Korektor</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pdfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pdf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="<?php echo e(route('pdf.detail', $pdf)); ?>" class="text-slate-700 hover:text-slate-900 hover:underline"><?php echo e($pdf->name); ?></a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->title->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->page_number ?? '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="<?php echo e($pdf->deadline_date->isPast() && $pdf->status !== 'completed' ? 'text-red-600 font-semibold' : ''); ?>">
                                    <?php echo e($pdf->deadline_date->format('d.m.Y H:i')); ?>

                                </span>
                            </td>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($pdf->assignedTo?->name ?? '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?php echo e(route('pdf.detail', $pdf)); ?>" class="text-slate-600 hover:text-slate-900 mr-3">Detail</a>
                                <a href="<?php echo e(route('pdf.download', $pdf)); ?>" class="text-slate-600 hover:text-slate-900 mr-3">Stáhnout</a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pdf->status === 'completed'): ?>
                                    <button wire:click="archivePdf(<?php echo e($pdf->id); ?>)" class="text-green-600 hover:text-green-900">Archivovat</button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">Žádná PDF k zobrazení.</td>
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
<?php /**PATH E:\Dev\TEST01\pdf-korektura\resources\views/livewire/dashboard.blade.php ENDPATH**/ ?>