<div class="col-sm-8 offset-sm-2 mt-5">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h3 fw-bold">
            References Management
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('viewReferences')); ?>" id="references_form">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="owner_model" class="form-label">Owner Model</label>
                    <select id="owner_model" class="form-select" name="owner_model">
                        <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($model); ?>" data-node-type="<?php echo e($model); ?>" <?php echo e(optional(optional($reference)->owner_model) ==$model || request('owner_model')==$model?"selected":''); ?>><?php echo e($model); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['owner_model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php if(request()->has('owner_model')): ?>
                <div class="mb-3">
                    <label for="owner_model_display_aid" class="form-label">Owner Model Display Aid</label>
                    <select id="owner_model_display_aid" class="form-select" name="owner_model_display_aid">
                        <?php $__currentLoopData = $model_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($field); ?>" data-node-type="<?php echo e($field); ?>" <?php echo e(request('owner_model_display_aid')==$field?"selected":''); ?>><?php echo e($field); ?></option>


                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['owner_model_display_aid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <?php endif; ?>
                <?php if(request()->has('owner_model_display_aid')): ?>
                <div class="mb-3">
                    <label for="owner_item" class="form-label">Owner Item</label>
                    <select id="owner_item" class="form-select" name="owner_item">
                        <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $owner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($owner->id); ?>" data-node-type="<?php echo e($owner[request('owner_model_display_aid')]); ?>"><?php echo e($owner[request('owner_model_display_aid')]); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['owner_model_display_aid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <?php endif; ?>


                
        </form>
    </div>
</div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Reference/Create.blade.php ENDPATH**/ ?>