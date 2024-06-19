<nav aria-label="Page navigation" class="mt-5">
    <ul class="pagination justify-content-center">
        <!-- Previous Page Link -->
        <li class="page-item">
            <a class="page-link" href="<?php echo e(route($route_name,['page'=>request()->get('page')-1,'search'=>request('search')])); ?>" aria-label="Previous">

                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#!" aria-label="Previous">
                <span aria-hidden="true"><?php echo e(request()->get('page')); ?>/<?php echo e($page_count); ?> </span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="<?php echo e(route($route_name,['page'=>request()->get('page')+1,'search'=>request('search')])); ?>" aria-label="Next">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Components/Pagination.blade.php ENDPATH**/ ?>