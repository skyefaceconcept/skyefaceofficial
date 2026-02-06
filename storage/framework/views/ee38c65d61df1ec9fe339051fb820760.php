<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['class' => '']));

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

foreach (array_filter((['class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Use company helper if available, fallback to default logo path
    $logo = null;
    try {
        $logo = \App\Helpers\CompanyHelper::logo();
    } catch (\Throwable $e) {
        $logo = null;
    }

    $src = $logo ? asset($logo) : asset('buzbox/img/logo-s.png');
?>

<img <?php echo e($attributes->merge(['class' => $class ?: 'block h-9 w-auto'])); ?> src="<?php echo e($src); ?>" alt="<?php echo e(config('app.name')); ?>">
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/components/application-mark.blade.php ENDPATH**/ ?>