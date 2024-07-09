<?php
    use Carbon\Carbon;
?>
<div class="col-sm-10 offset-sm-1">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="h4 fw-bold text-center">Fullname</th>
                        <th scope="col" class="h4 fw-bold text-center">Email</th>
                        <th scope="col" class="h4 fw-bold text-center">Created At</th>
                        <th scope="col" class="h4 fw-bold text-center">Last Login At</th>
                        <th scope="col" class="h4 fw-bold text-center">Role</th>
                        <th scope="col" class="h4 fw-bold text-center">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="text-center text-bg-light p-3 fw-semibold"><?php echo e($user->name); ?></div>

                        </td>
                        <td>
                            <div class="text-center text-bg-light p-3 fw-semibold"><?php echo e($user->email); ?></div>
                        </td>
                        <td>
                            <div class="text-center text-bg-light p-3 fw-semibold"><?php echo e(optional($user->created_at)->toDateTimeString()); ?></div>
                        </td>
                        <td>
                            <div class="text-center text-bg-light p-3 fw-semibold"><?php echo e(optional($user->last_login_at)->toDateTimeString()); ?></div>
                        </td>
                        <td>
                            <div class="text-center text-bg-light p-3 fw-semibold"><?php echo e($user->role_name); ?></div>

                        </td>
                        <td class="text-center">
                            <div class="text-bg-light p-3 fw-semibold">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view users assign roles button', auth()->user())): ?>
                                <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#assignRoleModal<?php echo e($user->id); ?>" title="assign role to user">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view users edit button', auth()->user())): ?>
                                <button type="button" class="btn btn-warning m-1 user_edit_button" data-bs-toggle="modal" data-bs-target="#editUserModal" title="edit user" data-user-id="<?php echo e($user->id); ?>">
                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                </button>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view users delete button', auth()->user())): ?>
                                <form action="<?php echo e(route('deleteUser',['user'=>$user])); ?>" method="post">
                                    <?php echo method_field('delete'); ?>
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-danger m-1" title="delete user"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </form>
                                <?php endif; ?></div>
                        </td>
                    </tr>
                    <div class="modal fade" id="assignRoleModal<?php echo e($user->id); ?>" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">

                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="assignRoleModalLabel">Assign Role To <?php echo e($user->name); ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?php echo e(route('assignRole',['user'=>$user,'page'=>request('page')])); ?>" method="post">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label for="role_name" class="form-label">Role</label>
                                            <select class="form-select" name="role">
                                                <option selected value="">Open this select menu</option>
                                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($role->id); ?>" <?php echo e(optional($user->role)->id==$role->id?"selected":''); ?>>
                                                    <?php echo e($role->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <div class="mt-2 text-center">
                                            <button type="submit" class="btn btn-primary"> <i class="fa fa-pencil" aria-hidden="true"></i></button>
                                        </div>

                                    </form>

                                </div>
                                
                            </div>
                        </div>
                    </div>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
                <?php echo $__env->make('Components.Pagination',['route_name'=>'viewUsers'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                
            </div>
        </div>
        <div class="modal fade " id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="assignRoleModalLabel">Update User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo e(route('updateUser',['user'=>1,'page'=>request('page')])); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div id="custom_input_user_fields"></div>
                            <div class="mt-2 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->startSection('scripts'); ?>
<script>
    const users = <?php echo json_encode($users, 15, 512) ?>

    const allEditBtns = document.querySelectorAll('.user_edit_button')
    if (allEditBtns) {
        allEditBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const current_user = users.filter(user => user.id == btn.getAttribute('data-user-id'))[0]
                document.querySelector('#custom_input_user_fields').innerHTML = current_user.updateHtml
                console.log(current_user)
            })

        })

    }
    console.log(users)

</script>

<?php $__env->stopSection(); ?>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/User/Table.blade.php ENDPATH**/ ?>