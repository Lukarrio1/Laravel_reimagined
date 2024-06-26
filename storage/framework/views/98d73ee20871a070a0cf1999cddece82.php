<?php $__env->startSection('content'); ?>
<div class="col-sm-8 offset-sm-2">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white  h3 fw-bold">
            Settings
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view settings edit or create form', auth()->user())): ?>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="key" class="form-label">Setting Key (<small class="text-primary">Please request the setting value by pressing the blue button.</small>)</label>
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
                <div class="mb-3">
                    <div class="mb-3">
                        <label for="role_name" class="form-label">Allowed for api use</label>
                        <select class="form-select" name="allowed_for_api_use">
                            <option selected>Open this select menu</option>
                            <?php $__currentLoopData = ['Yes'=>1,'No'=>0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e($allowed_for_api_use==$value?'selected':''); ?>><?php echo e($key); ?></option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                </div>

                <div class="mb-3 text-center mt-3">
                    <button class='btn btn-sm btn-warning' type="submit"><i class="fa fa-wrench" aria-hidden="true"></i></button>

                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view settings data table', auth()->user())): ?>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="text-center h4 fw-bold ">Setting</th>
                        <th scope="col" class="text-center h4 fw-bold ">key</th>
                        <th scope="col" class="text-center h4 fw-bold ">Value</th>
                        <th scope="col" class="text-center h4 fw-bold ">Allowed For Api Use</th>
                        <th scope="col" class="text-center h4 fw-bold ">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold"><?php echo e($setting->getAllSettingKeys($setting->key)); ?></div>
                        </td>

                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold"><span class="fw-bold"><?php echo e($setting->key); ?></span></div>
                        </td>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold"><?php echo $setting->getSettingValue('first'); ?></div>
                        </td>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold"><?php echo e($setting->allowed_for_api_use?"Yes":"No"); ?></div>
                        </td>
                        <td>
                            <div class="text-bg-light text-center p-3 fw-semibold">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view settings edit or create form',auth()->user())): ?>
                                <form class="mb-2">
                                    <input type="hidden" value="<?php echo e($setting->key); ?>" name="setting_key"></input>
                                    <button class=" btn btn-sm btn-warning" type="submit">
                                        <?php if(request('setting_key')==$setting->key): ?>
                                        <i class="fa fa-spinner" aria-hidden="true"></i>
                                        <?php else: ?>
                                        <i class="fa fa-wrench" aria-hidden="true"></i>
                                        <?php endif; ?>

                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view settings delete button',auth()->user())): ?>
                                <form action="<?php echo e(route('deleteSetting',['setting_key'=>$setting->key])); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('delete'); ?>
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Setting/View.blade.php ENDPATH**/ ?>