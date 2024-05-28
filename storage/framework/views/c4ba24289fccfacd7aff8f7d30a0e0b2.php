<?php $__env->startSection('content'); ?>
<div class="col-sm-8 offset-sm-2 mt-2 ">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">
            Refresh Cache
        </div>
        <div class="card-body">
            <div class="text-center">
                <a class="btn-warning btn" href="<?php echo e(route('clearCache')); ?>">refresh</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Cache/View.blade.php ENDPATH**/ ?>