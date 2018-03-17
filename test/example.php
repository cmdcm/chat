<?php

//----------------session操作
$session = Session::getInstance();
$session->set('q.q','test');
p($session->get('q'));
$session->set('test',666);
$session->set('test.q',999);
$session->delete('test');
p($session->get());
session('a','b');
p(session('a'));
session('a.test',666);
p(session('a'));
//----------------验证器
$data = ['name' => 'a','age' => '','eamil' => ''];
$rule = [
	'name'   => 'required|length:2,6',
	'age'	 => 'required',
	'eamil'  => 'eamil'
];
$message = [
	'name.required' => '姓名不能为空',
	'age.required'  => '年龄必须填写',
	'eamil'			=> '邮箱格式错误'
];
$valid  = Validator::make($rule,$message)->batch(true);
$result = $valid->check($data);
if (!$result) {
	p($valid->getError());
}

//--------------自定义验证
$data = ['name' => 'a'];
$rule = [
	'name'   => 'custom'
];
$message = [
	'name.custom' => '自定义验证错误'
];
$valid = Validator::make($rule,$message);
$valid->reigsterCheckHandle('custom',function ($value) {
	p($value);
	return false;
});
if (!$valid->check($data)) {
	p($valid->getError());
}