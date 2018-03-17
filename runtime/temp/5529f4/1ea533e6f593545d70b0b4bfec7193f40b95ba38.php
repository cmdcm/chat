<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>Chat</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="<?php echo e(asset('plugins/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('themes/animate.css')); ?>"/>
    <?php echo $__env->yieldContent('themes'); ?>
  </head>
  
  <body>
     
     <?php echo $__env->yieldContent('content'); ?>

     <script type="text/javascript" src="<?php echo e(asset('plugins/jquery/jquery.min.js')); ?>"></script>
     
     <?php echo $__env->yieldContent('scripts'); ?>
  
  </body>
</html>