<div class="col-sm-8 offset-sm-2">
    <div class="card  shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white text-center h4">
            Import Table Data
        </div>
        <div class="card-body">
            <form enctype="multipart/form-data" method="post" action='<?php echo e(route("importData")); ?>'>
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="table_name" class="form-label">Database Table</label>
                    <select class="form-select" aria-label="Default select example" name="table_name">
                        <option value=''>Open this select menu</option>
                        <?php $__currentLoopData = $table_names; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($name); ?>"><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['table_name'];
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
                    <label for="csv_file" class="form-label">Csv File</label>
                    <input type="file" name="csv_file" class="form-control" id="csv_file">
                    <?php $__errorArgs = ['csv_file'];
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
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" title="import csv file"><i class="fas fa-file-import"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Import/Import.blade.php ENDPATH**/ ?>