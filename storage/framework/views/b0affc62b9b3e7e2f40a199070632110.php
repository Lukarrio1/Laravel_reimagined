<div class="col-lg-8 offset-lg-2 mt-4">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white">
            <form action="<?php echo e(route('viewTenants')); ?>" action="get">
                <div class="mb-3">
                    <label for="search" class="form-label">
                        Tenants:<span class="badge text-bg-secondary">(<?php echo e(count($tenants)); ?>)</span>

                    </label>
                    
                </div>
            </form>
        </div>
        <div class="card-body bg-white">
            <table class="table bg-white">
                <thead>
                    <tr>
                        <th scope="col">Owner</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Api Base Url</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th scope="row"><?php echo e($tenant->owner->name); ?></th>
                        <th scope="row"><?php echo e($tenant->name); ?></th>
                        <td><?php echo e($tenant->email); ?></td>
                        <td><?php echo e($tenant->api_base_url); ?></td>
                        <td><?php echo e($tenant->description); ?></td>
                        <td><?php echo e($tenant->status['human_value']); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('editTenant',['tenant'=>$tenant])); ?>" class="btn btn-sm btn-warning m-2">
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                            </a>
                            <form action="<?php echo e(route('deleteTenant',['tenant'=>$tenant])); ?>" method="post">
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
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Multi_Tenancy/Table.blade.php ENDPATH**/ ?>