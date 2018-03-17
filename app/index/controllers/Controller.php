<?php
namespace app\index\controllers;
use concise\Controller as BaseController;
use concise\Request;
class Controller extends BaseController 
{
	public function __construct (Request $request)
	{
		if (empty(session('nickname'))) {
			return $request->redirect(url('login'));
		}
	}
}