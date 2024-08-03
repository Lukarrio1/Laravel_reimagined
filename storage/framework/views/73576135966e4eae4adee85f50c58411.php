<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h3 fw-bold">
            Permission Management
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('savePermission')); ?>" method='post'>
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="permission_name" class="form-label">Permission Name</label>
                    <input type="text" class="form-control" id="permission_name" value="<?php echo e(isset($permission)?optional($permission)->name:old('name')); ?>" name="name">
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
                <input type="hidden" value="<?php echo e(optional($permission)->id); ?>" name="id">
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-<?php echo e(isset($permission)?'warning':'primary'); ?>">
                        <?php if(isset($permission)): ?>
                        <i class="fa fa-wrench" aria-hidden="true"></i>
                        <?php else: ?>
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <?php endif; ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Permission/Create.blade.php ENDPATH**/ ?>