<?php $__env->startSection('content'); ?>
<div class="row">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can create or update references', auth()->user())): ?>
    <?php echo $__env->make('Reference.Create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view references', auth()->user())): ?>
    <?php echo $__env->make('Reference.Table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

</div>
<?php echo $__env->make('Reference.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Reference/View.blade.php ENDPATH**/ ?>