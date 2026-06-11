<div>
    <div class="mb-6">
        <a href="<?php echo e(route('dashboard')); ?>" class="text-slate-600 hover:text-slate-900 text-sm">&larr; Zpět na dashboard</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800"><?php echo e($pdfDocument->name); ?></h1>
                        <p class="text-gray-500 mt-1"><?php echo e($pdfDocument->title->name); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pdfDocument->issue_title): ?> &middot; <?php echo e($pdfDocument->issue_title); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        <?php if($pdfDocument->status === 'uploaded'): ?> bg-gray-100 text-gray-800
                        <?php elseif($pdfDocument->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                        <?php elseif($pdfDocument->status === 'returned'): ?> bg-yellow-100 text-yellow-800
                        <?php elseif($pdfDocument->status === 'completed'): ?> bg-green-100 text-green-800
                        <?php endif; ?>">
                        <?php echo e($pdfDocument->statusLabel()); ?>

                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
                    <div>
                        <p class="text-gray-500">Strana</p>
                        <p class="font-medium"><?php echo e($pdfDocument->page_number ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Deadline</p>
                        <p class="font-medium <?php echo e($pdfDocument->deadline_date->isPast() && $pdfDocument->status !== 'completed' ? 'text-red-600' : ''); ?>">
                            <?php echo e($pdfDocument->deadline_date->format('d.m.Y H:i')); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Vložil</p>
                        <p class="font-medium"><?php echo e($pdfDocument->uploadedBy->name); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Korektor</p>
                        <p class="font-medium"><?php echo e($pdfDocument->assignedTo?->name ?? 'Nepřiřazeno'); ?></p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <a href="<?php echo e(route('pdf.preview', $pdfDocument)); ?>" target="_blank"
                       class="bg-slate-800 text-white px-4 py-2 rounded-md hover:bg-slate-700 text-sm font-medium">Náhled v prohlížeči</a>
                    <a href="<?php echo e(route('pdf.download', $pdfDocument)); ?>"
                       class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 text-sm font-medium">Stáhnout</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Historie verzí</h3>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pdfDocument->versions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $version): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium">Verze <?php echo e($version->version_number); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($version->change_summary); ?></p>
                                <p class="text-xs text-gray-400 mt-1"><?php echo e($version->uploadedBy->name); ?> &middot; <?php echo e($version->created_at->format('d.m.Y H:i')); ?></p>
                            </div>
                            <a href="<?php echo e(route('pdf.download', ['pdfDocument' => $pdfDocument, 'version' => $version->version_number])); ?>"
                               class="text-slate-600 hover:text-slate-900 text-sm">Stáhnout</a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Historie aktivit</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pdfDocument->activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border-l-2 border-slate-300 pl-4">
                            <p class="text-sm font-medium"><?php echo e($log->actionLabel()); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($log->user?->name ?? 'Systém'); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->details): ?>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($log->details); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-400 mt-1"><?php echo e($log->created_at->format('d.m.Y H:i')); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH E:\Dev\TEST01\pdf-korektura\resources\views/livewire/pdf-detail.blade.php ENDPATH**/ ?>