@extends('layout.app')

@section('themes')
	<link rel="stylesheet" type="text/css" href="{{ asset('themes/index.css') }}" />
@endsection

@section('content')
	<div class="container">
		<div id="msg-div" class="animated slideInUp">{!! $message !!}</div>
		<form class="form-inline " action="javascript:;">
			<input type="text" class="form-control" required="" name="message" placeholder="" id="content">
			<button type="submit" class="btn btn-primary" id="submit">发言</button>
		</form>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{ asset('plugins/socket/socket.io.js') }}"></script>
	<script type="text/javascript">
		var msgBox   = $('#msg-div')
		  , socket 	 = io('http://chat.winterbest.cn:2021')
		  , nickname = "{{ session('nickname') }}";
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
@endsection