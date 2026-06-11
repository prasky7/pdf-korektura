<div>
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Audit log</h2>

    <div class="bg-white rounded-lg shadow mb-6">
        
        <div class="p-4 border-b border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Akce</label>
                    <select wire:model.live="actionFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                        <option value="">Všechny akce</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($action); ?>"><?php echo e($action); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Uživatel</label>
                    <select wire:model.live="userFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                        <option value="">Všichni uživatelé</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Titul</label>
                    <select wire:model.live="titleFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                        <option value="">Všechny tituly</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $titles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($title->id); ?>"><?php echo e($title->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Název PDF</label>
                    <input wire:model.live.debounce.300ms="pdfNameSearch" type="text" placeholder="Hledat název PDF..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Datum akce od</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Datum akce do</label>
                    <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Deadline od</label>
                    <input type="date" wire:model.live="deadlineFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Deadline do</label>
                    <input type="date" wire:model.live="deadlineTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm">
                </div>
            </div>
            <div class="mt-3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Hledat v detailech..."
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-500 text-sm w-64">
            </div>
        </div>

        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Čas akce</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uživatel</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akce</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Název PDF</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Originální soubor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titul</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detaily</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($log->created_at->format('d.m.Y H:i:s')); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($log->user?->name ?? 'Systém'); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs"><?php echo e($log->actionLabel()); ?></span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->pdfDocument): ?>
                                    <a href="<?php echo e(route('pdf.detail', $log->pdfDocument)); ?>" class="text-slate-600 hover:underline"><?php echo e($log->pdfDocument->name); ?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 font-mono" title="<?php echo e($log->pdfVersion?->file_path ?? ''); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->pdfVersion): ?>
                                    <?php echo e($log->pdfVersion->original_filename ?? basename($log->pdfVersion->file_path)); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($log->pdfDocument?->title?->name ?? '-'); ?>

                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->pdfDocument && $log->pdfDocument->deadline_date): ?>
                                    <span class="<?php echo e($log->pdfDocument->deadline_date->isPast() && $log->pdfDocument->status !== 'completed' ? 'text-red-600 font-semibold' : ''); ?>">
                                        <?php echo e($log->pdfDocument->deadline_date->format('d.m.Y H:i')); ?>

                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 max-w-md truncate"><?php echo e($log->details ?? '-'); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"><?php echo e($log->ip_address ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">Žádné záznamy v logu.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            <?php echo e($logs->links()); ?>

        </div>
    </div>
</div>
<?php /**PATH E:\Dev\TEST01\pdf-korektura\resources\views/livewire/admin/audit-log.blade.php ENDPATH**/ ?>