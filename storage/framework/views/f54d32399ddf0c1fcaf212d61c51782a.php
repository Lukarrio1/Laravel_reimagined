<div class="col-sm-8 offset-sm-2 mt-3">
    <div class="card  shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header text-center bg-white h4">
            User Management
        </div>
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Users :<span class="badge text-bg-secondary">(<?php echo e($users_count); ?>)</span></label>

                    <input type="text" class="form-control" name="search" value="<?php echo e(request()->get('search')); ?>">
                    <div id="" class="form-text">
                        <div class="mt-2 text-primary">Example Search Format: <?php echo e($search_placeholder); ?></div>
                    </div>
                </div>
                <div class="text-center"><button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button></div>

            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/User/Search.blade.php ENDPATH**/ ?>