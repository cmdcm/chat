<?php
namespace app\index\controllers;
use app\models\Message;
class IndexController extends Controller
{
	public function index () 
	{	
		$message = '';
		foreach (Message::select('nickname','content','create_time')->orderBy('create_time','ASC')->limit(15)->get() as $v) {
			$message .= sprintf("[%s] <span style='color:red'>%s</span> : %s<br/>\n",date('Y-m-d H:i:s',$v->create_time),$v->nickname,$v->content);
		}
		return view()->withMessage($message);
	}
}