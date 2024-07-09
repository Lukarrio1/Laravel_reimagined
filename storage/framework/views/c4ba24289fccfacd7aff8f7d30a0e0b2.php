<?php $__env->startSection('content'); ?>
<div class="col-sm-8 offset-sm-2 mt-2">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white  h3 fw-bold">
            Cache Managment
        </div>
        <div class="card-body  bg-white">

            <div class="">
                <form action="<?php echo e(route('clearCache')); ?>">

                    <div class="form-group">
                        <label>Select Cache to Clear</label>
                        <?php $__currentLoopData = $cacheOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="<?php echo e('option1'.$key); ?>" value="<?php echo e($key); ?>" name="<?php echo e($key); ?>">
                            <label class="form-check-label" for="<?php echo e('option1'.$key); ?>">
                                <?php echo e(collect(explode('_',$key))->map(fn($item)=>ucfirst($item))->join(' ')); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="text-center">
                        <button class="btn-success btn" type="submit"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                    </div>

                </form>
            </div>
        </div>
        


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Cache/View.blade.php ENDPATH**/ ?>