<?php $__env->startSection('themes'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('themes/index.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<div class="container">
		<div id="msg-div" class="animated slideInUp"><?php echo $message; ?></div>
		<form class="form-inline " action="javascript:;">
			<input type="text" class="form-control" required="" name="message" placeholder="" id="content">
			<button type="submit" class="btn btn-primary" id="submit">发言</button>
		</form>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
	<script type="text/javascript" src="<?php echo e(asset('plugins/socket/socket.io.js')); ?>"></script>
	<script type="text/javascript">
		var msgBox   = $('#msg-div')
		  , socket 	 = io('http://chat.winterbest.cn:2021')
		  , nickname = "<?php echo e(session('nickname')); ?>";
		socket.on('connect',function () {
			console.log('connect success!');
			socket.emit('userGoLine',nickname);
		});
		$('#submit').on('click',function () {
			if ($('#content').val().trim().length <= 0) return true;

			socket.emit('sendMessage',{
				nickname: nickname,
				message: $('#content').val()
			});
			$('#content').val('');
			return false;
		});
		socket.on('getMessage',function (message) {
			msgBox.append(message);
		});
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>