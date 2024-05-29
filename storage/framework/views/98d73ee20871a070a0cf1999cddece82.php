<?php $__env->startSection('content'); ?>
<div class="col-sm-8 offset-sm-2">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">
            Settings
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="key" class="form-label">Setting Key (<small class="text-danger">Please request the setting value by pressing the blue button.</small>)</label>
                    <select id="key" class="form-select" name="setting_key">
                        <?php $__currentLoopData = $keys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request()->get('setting_key')==$key?"selected":''); ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['setting_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color: red;"><?php echo e($message); ?></div> <!-- Display the error message -->
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="mb-3 text-center mt-3">
                        <button class='btn btn-sm btn-primary'>Request Setting</button>
                    </div>
                </div>
            </form>
            <form action="<?php echo e(route('saveSetting')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <input type='hidden' value="<?php echo e($setting_key); ?>" name="setting_key">
                    <label for="key" class="form-label">Setting Value</label>
                    <?php echo $key_value; ?>

                    <?php $__errorArgs = ['value'];
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
                <div class="mb-3 text-center mt-3">
                    <button class='btn btn-sm btn-warning' type="submit"><i class="fa fa-wrench" aria-hidden="true"></i></button>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Settings key</th>
                        <th scope="col">Settings Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($setting->getAllSettingKeys($setting->key)); ?></td>
                        <td><?php echo e(!empty($setting->getAllSettingKeys($setting->key))?$setting->getSettingValue('first'):''); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Setting/View.blade.php ENDPATH**/ ?>