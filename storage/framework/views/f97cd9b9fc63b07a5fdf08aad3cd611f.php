<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['align' => 'left', 'width' => '48']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['align' => 'left', 'width' => '48']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'relative'])); ?> x-data="{ open: false }">
    <div @click="open = ! open">
        <?php echo e($trigger ?? ''); ?>

    </div>

    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-<?php echo e($width); ?> rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1">
            <?php echo e($content ?? ''); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/components/dropdown.blade.php ENDPATH**/ ?>