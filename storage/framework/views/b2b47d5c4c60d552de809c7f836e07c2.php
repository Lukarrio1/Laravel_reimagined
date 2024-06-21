<div class="col-sm-8 offset-sm-2 mt-2">
    <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded">
        <div class="card-header bg-white">
            <div class="text-center h4">Routes, Pages, Links, Layouts & Components Management</div>
        </div>

        <div class="card-body">
            <form method="post" action="<?php echo e(route('saveNode')); ?>">
                <?php echo csrf_field(); ?>
                <?php if(isset($node)): ?>
                <input type="hidden" value="<?php echo e($node->id); ?>" name='id'>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Node Name</label>
                    <input type="text" class="form-control" id="node_name" aria-describedby="node_name" name="name" value="<?php echo e(optional($node)->name); ?>">
                    <?php $__errorArgs = ['name'];
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
                    <label for="node_description" class="form-label">Node Small Description</label>
                    <input type="text" class="form-control" id="node_description" name="small_description" value="<?php echo e(optional($node)->small_description); ?>">

                    <?php $__errorArgs = ['small_description'];
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
                    <label for="authentication_level" class="form-label">Node Authentication Level</label>
                    <select id="authentication_level" class="form-select" name="authentication_level">
                        <?php $__currentLoopData = $authentication_levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$auth): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(optional(optional($node)->authentication_level)['value']==$key?"selected":''); ?>><?php echo e($auth); ?></option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['authentication_level'];
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
                    <label for="node_type" class="form-label">Node Type</label>
                    <select id="node_type" class="form-select" name="node_type">
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type['id']); ?>" data-node-type="<?php echo e($key); ?>" <?php echo e(optional(optional($node)->node_type)['value'] ==$type['id']?"selected":''); ?>><?php echo e($key); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['node_type'];
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
                    <label for="node_status" class="form-label">Node Status</label>
                    <select id="node_status" class="form-select" name="node_status">
                        <?php $__currentLoopData = $node_statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" data-node-type="<?php echo e($status); ?>" <?php echo e(optional(optional($node)->node_status)['value']==$key?"selected":''); ?>><?php echo e($status); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['node_status'];
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
                    <div class="form-floating">
                    <span className="text-primary">Display verbiage eg: title:"Welcome to {-app_name-}"||welcome_message:"We are happy to see you {-user_name-}."</span>
                        <textarea class="form-control" placeholder="display verbiage .." id="floatingTextarea2" style="height: 200px" name="verbiage"><?php echo e(optional(optional($node)->verbiage)['value']); ?></textarea>
                    </div>
                </div>


                <div id="extra_fields"></div>
                <div class="mb-3">
                    <label for="permission" class="form-label">Node Permission</label>
                    <select id="permission" class="form-select" name="permission_id">
                        <option value=''> Select Permission</option>
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($permission->id); ?>" <?php echo e(optional($permission)->id==optional($node)->permission_id?"selected":''); ?>><?php echo e($permission->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['node_status'];
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

                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-<?php echo e(isset($node)?'warning':'primary'); ?>">
                        <?php if(isset($node)): ?>
                        <i class="fa fa-wrench" aria-hidden="true"></i>
                        <?php else: ?>
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <?php endif; ?>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<?php echo $extra_scripts; ?>

<script>
    const types = <?php echo json_encode($types, 15, 512) ?>


    const node_type = document.querySelector('#node_type')

    setTimeout(() => {
        if (<?php echo json_encode($node, 15, 512) ?> != null) {
            // Set the select's value
            node_type.value = <?php echo json_encode($node, 15, 512) ?>['node_type']['value'];
            // Optional: Trigger an event to simulate user interaction (e.g., change)
            node_type.dispatchEvent(new Event('change'));

        }
    }, 1000)



    const extra_fields = document.querySelector('#extra_fields')

    if (node_type)
        node_type.addEventListener('change', function(event) {
            // Get the selected option
            if (node_type.options == null) return
            const selectedOption = node_type.options[node_type.selectedIndex];
            // Access the data attributes
            const customValue = selectedOption.getAttribute('data-node-type');
            const selected = customValue
            const current_type = types[selected]
            extra_fields.innerHTML = current_type?.extra_html ? current_type?.extra_html : ''
        });

</script>
<?php $__env->stopSection(); ?>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Nodes/Create.blade.php ENDPATH**/ ?>