<?php
namespace app\api\controller;
use think\Controller;
use think\Config;
use apidoc\Api;

class Index extends Controller
{
	// public $apiinfo = '';
	// public $classinfo= '';
	// public $class = '';
	public function _initialize()
	{
		$this->class = new Api();
	}

	public function getClass()
	{
		return $this->class->GetClassDoc(__CLASS__);
	}

	public function index()
	{
        $this->class = new Api();

        // $this->assign('classinfo',$this->getClass());
        return view('Api/index');
	}
}
