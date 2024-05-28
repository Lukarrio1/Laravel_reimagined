<nav aria-label="Page navigation" class="mt-5">
    <ul class="pagination justify-content-center">
        <!-- Previous Page Link -->
        <li class="page-item">
            <a class="page-link" href="<?php echo e(route($route_name).'?page='.request()->get('page')-1); ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="!#" aria-label="Previous">
                <span aria-hidden="true"><?php echo e(request()->get('page')); ?> </span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="<?php echo e(route($route_name).'?page='.request()->get('page')+1); ?>" aria-label="Next">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Components/Pagination.blade.php ENDPATH**/ ?>