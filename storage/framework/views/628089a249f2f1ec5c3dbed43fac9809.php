<div class="col-sm-4 offset-sm-1">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white h4 text-center">Last updated api route</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php $__currentLoopData = $last_used_routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item text-center h6"><?php echo e($route->name); ?> was used at <?php echo e($route->created_at->diffForHumans()); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div class="card-footer">
            <div class="text-center">
                <a href="<?php echo e(route('viewNodes')); ?>" class="btn btn-sm btn-primary">view updates</a>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Dashboard/LastUsedRoute.blade.php ENDPATH**/ ?>