<?php $__env->startSection('themes'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('themes/login.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="container">
		<form action="<?php echo e(url('login/login')); ?>" method="post">
			<div class="form-group animated slideInLeft">
				<input type="text" class="form-control" name="nickname" placeholder="请输入您的昵称" required="" maxlength="10">
			</div>
			<button type="submit" class="btn btn-danger btn-block animated slideInRight">开始聊天</button>
		</form>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>