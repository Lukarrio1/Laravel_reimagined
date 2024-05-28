<div class="col-sm-8 offset-sm-2 mt-2">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">Tenant Management</div>
        <div class="card-body">
            <form action="<?php echo e(route('updateOrCreateTenant')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <?php if(isset($tenant)): ?>
                <input type="hidden" name="id" value="<?php echo e($tenant->id); ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="tenant_name" class="form-label">Tenant Name</label>
                    <input type="text" class="form-control" id="tenant_name" name="name" value="<?php echo e(isset($tenant)?$tenant->name:''); ?>">
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
                    <label for="tenant_email" class="form-label">Tenant Email</label>
                    <input type="email" class="form-control" id="tenant_email" name="email" value="<?php echo e(isset($tenant)?$tenant->email:''); ?>">
                    <?php $__errorArgs = ['email'];
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
                    <label for="tenant_description" class="form-label">Tenant Small Description</label>
                    <input type="text" class="form-control" id="tenant_description" name="description" value="<?php echo e(isset($tenant)?$tenant->description:''); ?>">
                    <?php $__errorArgs = ['description'];
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
                    <label for="tenant_status" class="form-label">Tenant Active Status</label>
                    <select id="tenant_status" class="form-select" name="status">
                        <?php $__currentLoopData = ['Active'=>1,'Inactive'=>0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(isset($tenant)&&$key==$tenant->status['value']?"selected":''); ?>><?php echo e($value); ?></option>\
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <?php if(isset($tenant)): ?>
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
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Multi_Tenancy/Create.blade.php ENDPATH**/ ?>