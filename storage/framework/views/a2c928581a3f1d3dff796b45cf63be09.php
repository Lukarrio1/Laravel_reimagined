<?php $__env->startSection('content'); ?>
<div class="row">

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("can view new users dashboard component",auth()->user())): ?>
    <?php echo $__env->make('Dashboard.NewUser', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("can view last update api route dashboard component",auth()->user())): ?>
    <?php echo $__env->make('Dashboard.LastUsedRoute', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("can view audit history dashboard component",auth()->user())): ?>
    <?php echo $__env->make('Dashboard.AuditHistory', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Dashboard/View.blade.php ENDPATH**/ ?>