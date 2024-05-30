<?php $__env->startSection('content'); ?>
<div class="row">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view permissions edit or create form', auth()->user())): ?>
    <?php echo $__env->make('Permission.Create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view permissions data table', auth()->user())): ?>
    <?php echo $__env->make('Permission.Table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Permission/View.blade.php ENDPATH**/ ?>