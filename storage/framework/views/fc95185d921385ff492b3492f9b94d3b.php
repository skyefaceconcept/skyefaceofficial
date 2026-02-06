<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3" role="alert">
        <p class="font-bold"><?php echo e(session('status')); ?></p>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/components/banner.blade.php ENDPATH**/ ?>