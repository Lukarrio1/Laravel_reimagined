<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">
            Export Table Data
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('exportData')); ?>" method="get">
                <div class="mb-3">
                    <label for="table" class="form-label">Table</label>
                    <select class="form-select" aria-label="Default select example" name="table">
                        <option selected value=''>Open this select menu</option>
                        <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($table); ?>" <?php echo e(request('table')==$table?"selected":''); ?>><?php echo e($table); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($table_error!=1): ?>
                    <div style="color: red;"><?php echo e($table_error); ?></div> <!-- Display the error message -->
                    <?php endif; ?>

                </div>
                <?php if(count($table_columns)>0&&request('table')!=null): ?>
                <div class="mb-3">
                    <label for="table" class="form-label">Table Columns</label>
                    <select class="form-select" aria-label="Default select example" name="table_columns[]" multiple size="5">
                        <option>Open this select menu</option>
                        <?php $__currentLoopData = $table_columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($column); ?>"><?php echo e($column); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['table_error'];
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
                    <label for="exampleInputEmail1" class="form-label">Advanced <?php echo e(ucfirst(request('table'))); ?> Search (<?php echo e(count($table_data)); ?>)
                    </label>
                    <input type="text" class="form-control" name="search" value="<?php echo e(request()->get('search')); ?>">
                    <div id="" class="form-text">
                        <div class="mt-2 text-primary">Example Search Format: <?php echo e($searchPlaceholder); ?></div>
                    </div>
                </div>

                <?php endif; ?>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" title="filter table data"><i class="fas fa-filter"></i></button>

                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Export/Filter.blade.php ENDPATH**/ ?>