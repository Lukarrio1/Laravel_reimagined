<?php $__env->startSection('content'); ?>
<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>

<div class="row">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view nodes edit or create form', auth()->user())): ?>
    <?php echo $__env->make('Nodes.Create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can view nodes data table', auth()->user())): ?>
    <?php echo $__env->make('Nodes.Table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Nodes/View.blade.php ENDPATH**/ ?>