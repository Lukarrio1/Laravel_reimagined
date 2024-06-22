<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h3 fw-bold">

            Role Management
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('saveRole')); ?>" method='post'>
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name</label>
                    <input type="text" class="form-control" id="role_name" aria-describedby="emailHelp" value="<?php echo e(isset($role)?optional($role)->name:old('name')); ?>" name="name">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: red;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>
                <div class="mb-3">
                    <label for="role_priority" class="form-label">Role priority</label>
                    <input type="number" class="form-control" id="role_priority" aria-describedby="emailHelp" value="<?php echo e(isset($role)?optional($role)->priority:old('priority')); ?>" name="priority">
                    <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: red;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="role_name" class="form-label">Permissions (<small class="text-primary">Use shift to select more than 1 permission</small>)</label>
                    <select class="form-select" multiple aria-label="Multiple select example" name="permissions[]">
                        <option selected>Open this select menu</option>
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($permission->id); ?>" <?php echo e(in_array($permission->id,empty(optional(optional($role)->permissions)->pluck('id'))?[]:
                        optional(optional($role)->permissions)->pluck('id')->toArray()) ? 'selected' : ''); ?>>
                            <?php echo e($permission->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <input type="hidden" value="<?php echo e(optional($role)->id); ?>" name="id">
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-<?php echo e(isset($role)?'warning':'primary'); ?>">
                        <?php if(isset($role)): ?>
                        <i class="fa fa-wrench" aria-hidden="true"></i>
                        <?php else: ?>
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <?php endif; ?></button>

                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Role/Create.blade.php ENDPATH**/ ?>