<?php $__env->startSection('title', '主页'); ?>
<?php $__env->startSection('content'); ?>
    <div class="jumbotron">
        <h1>Hello Laravel</h1>
        <p class="lead">
            你现在所看到的是 <a href="https://laravel-china.org/laravel-tutorial/5.1">Laravel 入门教程</a> 的示例项目主页。
        </p>
        <p>
            一切，将从这里开始。
        </p>
        <p>
            <a class="btn btn-lg btn-success" href="<?php echo e(route('signup')); ?>" role="button">现在注册</a>
        </p>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>