<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white h6">
            <form action="<?php echo e(route('viewRoles')); ?>" action="get">
                <div class="mb-3">
                    <label for="search" class="form-label h3">
                        <span class="badge text-bg-secondary"> Roles: (<?php echo e(!empty($search)?$roles_count.'/'.$roles_count_overall:$roles_count); ?>)</span>
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
                        <th scope="col" class="text-center  h4 fw-bold">Role Name</th>

                        <th scope="col" class="text-center  h4 fw-bold">Role Priority</th>

                        <th scope="col" class="text-center  h4 fw-bold">Role Permissions</th>

                        <th scope="col" class="text-center  h4 fw-bold">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center">
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Role['name']); ?></div>
                        </td>
                        <td class="text-center">
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Role['priority']); ?></div>
                        </td>
                        <td class="text-center">
                            <div class="text-bg-light p-3 fw-semibold">
                                <ul class="list-group-flush">
                                    <?php $__currentLoopData = $Role['permission_name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="list-group-item">
                                        <bold><?php echo e($name); ?></bold>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="text-bg-light p-3 fw-semibold">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view roles edit button', auth()->user())): ?>
                                <a href="<?php echo e(route('editRole',['role'=>$Role['id']])); ?>" class="btn btn-sm btn-warning m-2">
                                    <?php if(optional($role)->id==$Role['id']): ?>
                                    <i class="fa fa-spinner" aria-hidden="true"></i>
                                    <?php else: ?>
                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                    <?php endif; ?>
                                </a>
                                <?php endif; ?>
                                <?php if($Role['core']!=1): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view roles delete button', auth()->user())): ?>
                                <form action="<?php echo e(route('deleteRole',['role'=>$Role['id']])); ?>" method="post">
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

                <?php echo $__env->make('Components.Pagination',['route_name'=>'viewRoles'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                
            </div>

        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Role/Table.blade.php ENDPATH**/ ?>