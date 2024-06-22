<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white h6">
            <form action="<?php echo e(route('viewPermissions')); ?>" action="get">
                <div class="mb-3">
                    <label for="search" class="form-label h3">
                        <span class="badge text-bg-secondary"> Permissions: (<?php echo e(!empty($search)?$permissions_count.'/'.$permissions_count_overall:$permissions_count); ?>)</span>
                    </label>
                    <input type="text" class="form-control" name="search" value="<?php echo e($search); ?>" placeholder="Search...">
                    <div class="mt-2 text-primary">Example Search Format:<?php echo e($searchPlaceholder); ?></div>
                </div>
            </form>

        </div>

        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="text-center  h4 fw-bold">Permission Name</th>
                        <th scope="col" class="text-center  h4 fw-bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center">
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Permission->name); ?></div>
                        </td>

                        <td class="text-center">
                            <div class="text-bg-light p-3 fw-semibold">
                                <?php if($Permission->core!=true): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view permissions edit button',auth()->user())): ?>
                                <a href="<?php echo e(route('editPermission',['permission'=>$Permission])); ?>" class="btn btn-sm btn-warning m-2">
                                    <?php if(optional($permission)->id==$Permission->id): ?>
                                    <i class="fa fa-spinner" aria-hidden="true"></i>
                                    <?php else: ?>
                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                    <?php endif; ?>

                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view permissions delete button', auth()->user())): ?>
                                <form action="<?php echo e(route('deletePermission',['permission'=>$Permission])); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('delete'); ?>
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
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