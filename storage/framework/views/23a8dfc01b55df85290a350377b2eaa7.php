<?php
use Illuminate\Support\Facades\Session;

$app_name =optional(collect(Cache::get('settings'))->where('key','app_name')->first())->properties;
$app_version =optional(collect(Cache::get('settings'))->where('key','app_version')->first())->properties;
$app_animation =optional(collect(Cache::get('settings'))->where('key','app_animation')->first())->getSettingValue('last');
$multi_tenancy =(int)optional(collect(Cache::get('settings'))->where('key','multi_tenancy')->first())->getSettingValue('first');

?>

<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .scrollable-div {
            width: auto;
            height: auto;
            overflow: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

    </style>
    <style>
        body {
            font-family: Garamond, serif;
        }

    </style>


    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
</head>
<body>
    <div id="app">
        <?php echo $__env->make('Components.Navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main class="py-4 container-fluid">
            <div class="<?php echo e($app_animation); ?>">
                <?php if(Session::has('message')): ?>
                <p class="alert text-center <?php echo e(Session::get('alert-class', 'alert-info')); ?>"><?php echo e(Session::get('message')); ?></p>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
        <?php echo $__env->yieldContent('scripts'); ?>
        <footer class="footer bg-white fixed-bottom">
            <div class="container text-center py-3">
                <span>Version: <?php echo e($app_version); ?></span>
            </div>
        </footer>

    </div>
</body>
</html>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/layouts/app.blade.php ENDPATH**/ ?>