<?php
namespace app\index\controllers;
use concise\Request;
class LoginController
{
	public function index ()
	{
		return view();
	}
	public function login (Request $request)
	{
		session('nickname',$request->post('nickname'));
		return $request->redirect(url());
	}
}