<div class="col-lg-12 mt-4">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white">
            <form action="<?php echo e(route('viewNodes')); ?>" action="get">
                <div class="mb-3">
                    <label for="search" class="form-label h3">
                        <span class="badge text-bg-secondary"> Nodes: (<?php echo e(!empty($search)?$nodes_count.'/'.$nodes_count_overall:$nodes_count); ?>)</span>
                    </label>
                    <input type="text" class="form-control" id="node_search" name="search" value="<?php echo e($search); ?>" placeholder="Search...">
                    <div class="mt-2 text-primary">Example Search Format: <?php echo e($search_placeholder); ?>, press enter to search</div>
                </div>
            </form>
        </div>
        <div class="card-body scrollable-div">
            <table class="table table-white table-hover">

                <thead>
                    <tr>
                        <th scope="col" class="text-center h4 fw-bold ">Name</th>

                        <th scope="col" class="text-center h4 fw-bold">Description</th>

                        <th scope="col" class="text-center h4 fw-bold">Authentication Level</th>

                        <th scope="col" class="text-center h4 fw-bold">Type</th>

                        <th scope="col" class="text-center h4 fw-bold">Status</th>

                        <th scope="col" class="text-center h4 fw-bold">Permission</th>

                        <th scope="col" class="text-center h4 fw-bold">Verbiage</th>

                        <th scope="col" class="text-center h4 fw-bold">UUID</th>

                        <th scope="col" class="text-center h4 fw-bold">Properties</th>

                        <th scope="col" class="text-center h4 fw-bold">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $nodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Node): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="text-bg-light p-3 fw-semibold"><strong><?php echo e($Node->name); ?></strong></div>
                        </td>
                        <td>
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Node->small_description); ?></div>
                        </td>
                        <td>
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Node->authentication_level['human_value']); ?></div>
                        </td>

                        <td>
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Node->node_type['human_value']); ?></div>
                        </td>

                        <td>
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Node->node_status['human_value']); ?></div>
                        </td>

                        <td>
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e(optional(optional($Node)->permission)->name); ?></div>
                        </td>
                        <td>
                            <ul class="list-group list-group-flush pt-2">
                                <?php $__currentLoopData = collect($Node->verbiage['human_value'])->keys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item text-center">
                                    <?php echo e($key.": ".$Node->verbiage['human_value'][$key]); ?>

                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>
                        <td>
                            <div class="text-bg-light p-3 fw-semibold"><?php echo e($Node->uuid); ?></div>
                        </td>
                        <td><?php echo $Node->properties['html_value']; ?></td>
                        <td>
                            <ul class="list-group list-group-flush pt-2">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view nodes edit button', auth()->user())): ?>
                                <li class="list-group-item text-center">
                                    <a href="<?php echo e(route('viewNode',['node'=>$Node])); ?>" class="btn btn-warning btn-sm m-2 h4" title="edit node">
                                        <?php if(optional($node)->id==$Node->id): ?>
                                        <i class="fa fa-spinner" aria-hidden="true"></i>
                                        <?php else: ?>
                                        <i class="fa fa-wrench" aria-hidden="true"></i>
                                        <?php endif; ?>

                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view nodes delete button', auth()->user())): ?>
                                <li class="list-group-item text-center">
                                    <form action="<?php echo e(route('deleteNode',['node'=>$Node])); ?>" method="post">
                                        <?php echo method_field('delete'); ?>
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-danger btn-sm h4" title="delete node">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </li>
                                <?php endif; ?>

                            </ul>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <div class="text-center">
                <?php echo $__env->make('Components.Pagination',['route_name'=>'viewNodes','page_count'=>$page_count], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
    const searchField = document.querySelector('#node_search')
    if (searchField) {
        searchField.addEventListener('input', (e) => {
            localStorage.setitem('node_search', e.target.value)
        })
    }

</script>
<?php $__env->stopSection(); ?>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Nodes/Table.blade.php ENDPATH**/ ?>