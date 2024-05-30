<?php if(request('table')!=null): ?>
<div class="col-sm-12">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header h4 text-left">
            Current Table: <?php echo e(strtoupper(request('table'))); ?>

        </div>
        <div class="card-body scrollable-div">
            <table class="table table-responsive-xxl">

                <thead>
                    <tr>
                        <?php $__currentLoopData = $selected_table_columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th scope="col"><?php echo e(collect(explode('_',$column))->map(fn($word)=>ucfirst($word))->join(' ')); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $table_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php $__currentLoopData = collect($data)->keys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td><small><?php echo e(collect($data)->get($key)); ?></small></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        </div>
        <div class="card-footer bg-white">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view export button', auth()->user())): ?>
            <div class="text-center">
                <form action="<?php echo e(route('exportDataNow')); ?>">
                    <input type="hidden" value="true" name="export">
                    <button type="submit" class="btn btn-success btn-lg">Export Data</button>
                </form>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php endif; ?>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Export/Table.blade.php ENDPATH**/ ?>