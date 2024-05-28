 <?php
 use Illuminate\Support\Facades\URL;
 ?>

 <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
     <div class="container">
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('home'))? 'red' : 'black'); ?>" href="<?php echo e(url('/')); ?>">
             <?php echo e($app_name); ?>

         </a>
         <?php if(auth()->guard()->check()): ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can crud nodes',auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('viewNodes'))? 'red' : 'black'); ?>" href="<?php echo e(route('viewNodes')); ?>" aria-current="page">
             Nodes
         </a>
         <?php endif; ?>

         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can crud roles',auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('viewRoles'))? 'red' : 'black'); ?>" href="<?php echo e(route('viewRoles')); ?>">Roles</a>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can crud permissions',auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('viewPermissions'))? 'red' : 'black'); ?>" href="<?php echo e(route('viewPermissions')); ?>">Permissions</a>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can crud users',auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('viewUsers'))? 'red' : 'black'); ?>" href="<?php echo e(route('viewUsers')); ?>">Users</a>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can clear cache', auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('viewCache'))? 'red' : 'black'); ?>" href="<?php echo e(route('viewCache')); ?>">Cache</a>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can export',auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('exportData'))? 'red' : 'black'); ?>" href="<?php echo e(route('exportData')); ?>">Export</a>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can import', auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('importView'))? 'red' : 'black'); ?>" href="<?php echo e(route('importView')); ?>">Import</a>
         <?php endif; ?>
         <?php if($multi_tenancy==1): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('viewTenants'))? 'red' : 'black'); ?>" href="<?php echo e(route('viewTenants')); ?>">Multi Tenancy</a>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('can crud settings', auth()->user())): ?>
         <a class="navbar-brand" style="color:<?php echo e(request()->url()==URL::to(route('viewSettings'))? 'red' : 'black'); ?>" href="<?php echo e(route('viewSettings')); ?>">Settings</a>
         <?php endif; ?>
         <?php endif; ?>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
             <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
             <!-- Left Side Of Navbar -->
             <ul class="navbar-nav me-auto">

             </ul>

             <!-- Right Side Of Navbar -->
             <ul class="navbar-nav ms-auto">
                 <!-- Authentication Links -->
                 <?php if(auth()->guard()->guest()): ?>
                 <?php if(Route::has('login')): ?>
                 <li class="nav-item">
                     <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                 </li>
                 <?php endif; ?>

                 <?php if(Route::has('register')): ?>
                 <li class="nav-item">
                     <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                 </li>
                 <?php endif; ?>
                 <?php else: ?>
                 <li class="nav-item dropdown">
                     <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                         <?php echo e(Auth::user()->name); ?>

                     </a>

                     <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                         <a class="dropdown-item" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                             <?php echo e(__('Logout')); ?>

                         </a>

                         <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                             <?php echo csrf_field(); ?>
                         </form>
                     </div>
                 </li>
                 <?php endif; ?>
             </ul>
         </div>
     </div>
 </nav>
<?php /**PATH /Users/niritechuser15/Documents/Development/laravel-reimagined/resources/views/Components/Navbar.blade.php ENDPATH**/ ?>