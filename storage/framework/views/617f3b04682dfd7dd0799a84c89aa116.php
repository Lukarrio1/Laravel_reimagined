<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="bg-white card-header">
            Permissions:<span class="badge text-bg-secondary">(<?php echo e($permissions_count); ?>)</span>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Permission Name</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($permission->name); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('editPermission',['permission'=>$permission])); ?>" class="btn btn-sm btn-warning m-2">
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                            </a>
                            <form action="<?php echo e(route('deletePermission',['permission'=>$permission])); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('delete'); ?>
                                <button class="btn btn-sm btn-danger" type="submit">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
            <?php echo $__env->make('Components.Pagination',['route_name'=>'viewPermissions'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Permission/Table.blade.php ENDPATH**/ ?>