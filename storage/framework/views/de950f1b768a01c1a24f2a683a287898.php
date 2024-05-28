<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white h6">
            Roles:<span class="badge text-bg-secondary">(<?php echo e($roles_count); ?>)</span>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Role Name</th>
                        <th scope="col" class="text-center">Role Permissions</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($role['name']); ?></td>

                        <td class="text-center">
                            <ul class="list-group-flush">
                                <?php $__currentLoopData = $role['permission_name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item"><?php echo e($name); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('editRole',['role'=>$role['id']])); ?>" class="btn btn-sm btn-warning m-2">
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                            </a>
                            <form action="<?php echo e(route('deleteRole',['role'=>$role['id']])); ?>" method="post">
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

              <?php echo $__env->make('Components.Pagination',['route_name'=>'viewRoles'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                
            </div>

        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Role/Table.blade.php ENDPATH**/ ?>