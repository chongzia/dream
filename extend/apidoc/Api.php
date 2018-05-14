<?php
////////////////////////////////////////////////////
// 对于class中function数组的特殊说明                        //
// 在class下 的functionname作用是 get传值的链接使用功能，需要查找的api //
// 在函数下的functionname仅仅作为函数中的展示显示功能                //
////////////////////////////////////////////////////
namespace apidoc;

class Api
{
	// 反射类返回调用的类
	private static $refclass = null;
	// 反射类返回调用的类内函数
	private static $reffunction = null;
	// 类信息内容
	private static $classbase = [];
	// 函数信息内容
	private static $functionbase = [];
	// 自定义需要显示的类的文档内容
	public static $setClassAttribute =
	[
		'@author',
		'@title',
		'@date',
		'@version',
		'@todo',
		'@describe',
	];
	// 自定义需要显示的类的函数内容内容
	public static $setFunctionAttribute =
	[
		'@api',
		'@author',
		'@title',
		'@request',
		'@date',
		'@version',
		'@todo',
		'@describe',
		'@param',
		'@return',
	];
	//自定义不用显示的方法
	public static $setRemoveFunctionName =
	[
		'__construct',
		'__destruct',
		'__call',
		'__callStatic',
		'__get',
		'__set',
		'__isset',
		'__unset',
		'__sleep',
		'__wakeup',
		'__toString',
		'__invoke',
		'__set_state',
		'__sleep',
		'__debugInfo',
		// 文档不需要显示的内容
		'getClassInfo',
		'_initialize',
		'index',
	];
	// 自定义自己需要的参数或返回值类型
	public static $setParameterType =
	[
		'int',
		'string',
		'float',
		'bool',
		'array',
		'object',
		'null',
		'resource',
	];

#=============================================================================================================
	// 设置反射类
	private static function setClass($classname)
	{
		// 初始化反射类对象
		self::$refclass = new \ReflectionClass($classname);
	}
	// 读取类基本信息
	private static function getClassBase($classname)
	{
		// 读取类名称
		self::$classbase['classname'] = self::$refclass->getName().PHP_EOL;
		// 类所在路径
		self::$classbase['classurl'] = dirname(self::$refclass->getFileName()).PHP_EOL;
		// 读取类文件名称
		self::$classbase['classfile'] = basename(self::$refclass->getFileName()).PHP_EOL;
		// 拼装httpuri地址
		self::$classbase['classhttpurl'] = 'api/'.explode('\\', $classname)[3].'/index';
		// 返回传入的类名
		self::$classbase['classshowname'] = explode('\\', $classname)[3];
		// 读取类内函数名
		self::$classbase['functionname'] = self::$refclass->getMethods();
		// 读取类内容注释信息
		self::$classbase['apidoc'] = self::$refclass->getDocComment();
	}

	// 处理类函数对象
	private static function ClassHandleFunctionObj()
	{
		// 定义一个空变量数组接收最终结果
		$data = [];
		// 循环类内的函数对象
		foreach (self::$classbase['functionname'] as $k => $v)
		{
			// 如果是一个对象
			if (gettype($v) == 'object')
			{
				// 如果函数名是think下的信息，则删除该部分信息
				if ($v->class == 'think\Controller') unset($v);
				// 否则将函数名存储起来
				else $data[] = $v->name;
			}
		}
		// 从新对函数进行处理取出函数名即可
		self::$classbase['functionname'] = $data;
	}

	// 处理类下函数名部分内容
	private static function ClassHandleFunctionName()
	{
		//定义一个空变量数组接收处理结果
		$res = [];
		// 处理类下所有函数使用需求
		foreach (self::$classbase['functionname'] as $key => $val)
		{
			// 如果内容存在于数组中，删除内容
			if(in_array($val,self::$setRemoveFunctionName)) unset($key);
			// 否则记录内容
			else $res[] = $val;
		}
		// 否则将函数名存储起来
		self::$classbase['functionname'] = $res;
	}

	// 处理类功能多余内容显示部分
	private static function ClassHandleSuperfluousContent()
	{
		// 定义一个空变量数组
		$data = [];
		// 数组拆分传入的注释内容
		$doc = explode(PHP_EOL,  self::$classbase['apidoc']);
		// 统计拆分后数组长度
		$num = count($doc);
		// 循环内容取出多余并不需要的内容
		foreach ($doc as $key => $val)
		{
			// 如果数组下标是以下内容则删除该内容
			if (@$key === 0 || @$key === $num || @$key === ($num-1) ||  @$key === ($num-2)) unset($key);
			// 否则将内容存储起来 处理前置内容，清除空格 *符号
			else $data[] = trim(str_replace('*','',$val));
		}
		// 指定存入类文档中
		self::$classbase['apidoc'] = $data;
	}

	// 处理类功能前置内容，从新整理键与值的内容
	private static function ClassHandlePreContent()
	{
		// 定义一个空变量数组
		$data = [];
		// 循环内容处理所有多余符号
		foreach (self::$classbase['apidoc'] as $key => $val)
		{
			// 匹配到注释内容
			preg_match("/@[\w]*/i", $val, $res);
			// 如果匹配结果存在是@类型注释则进行的内容
			if (isset($res[0]))
			{
				// 以空格为准拆分内容信息
				$content = explode(' ',$val);
				// 如果统计结果为2则说明是正常数据 将拆分的内容分别存储到键与值中
				if (count($content) == 2) $data['normal'][$content[0]] = $content[1];
				// 如果统计结果不为2则放入索引数组中
				else $data[] = $val;
			}
			// 如果匹配结果存在是数字类型的注释则匹配的内容
			else
			{

				// 拆分其他信息
				$other = explode(' ',$val);
				// 如果内容是数字，则放入索引其他数字内容中等待进一步处理
				if (is_numeric($other[0])) $data['other'][][$other[0]] = $other[1];
				// 否则直接放入索引数字中进行登录下一步操作
				else $data[] = $val;
			}
		}
		// 拆分出部分可用信息 简化文档内容 ，以及如果部分文档内容报错的处理方式
		if (!isset($data['normal']["@describe"]) || $data['normal']["@describe"] == null) self::$classbase['@describe'] = null;
		else  self::$classbase['@describe'] = $data['normal']["@describe"];
		if (!isset($data['normal']["@todo"]) ||$data['normal']["@todo"] == null)  self::$classbase['@todo'] = null;
		else  self::$classbase['@todo'] = $data['normal']["@todo"];
		// 删除已经拆分的数据内容
		unset($data['normal']["@describe"]);
		unset($data['normal']["@todo"]);
		self::$classbase['other'] = $data['other'];
		self::$classbase['normal'] = $data['normal'];
		self::$classbase['apidoc'] = $data;
	}

	// 处理类里面需要显示与展示的内容
	private static function ClassHandleContentError()
	{
		// 正对api文档进行处理
		$list = self::$classbase['apidoc'];
		// 定义一个接收信息的空数组
		$data = [];
		// 循环传入内容
		foreach ($list as $key => &$val)
		{
			// 如果内容出现以下2中判断情况 则显示错误 1是数据为空 2是键名为空
			if (is_numeric($key) && $val == '') self::$classbase['error'][] = $val;
			if (is_numeric($key)){ self::$classbase['error'][] = $val;}
		}
		// 删除api文档
		unset(self::$classbase['apidoc']);
		foreach (self::$classbase['error'] as $key => $val)
		{
			if ($val != '') $data[] = $val;
		}
		self::$classbase['error'] = $data;
	}

	//处理类功能的描述
	private static function ClassHandleOther()
	{
		// 初始化类内容文档说明部分用于使用说明与计划说明
		$count = 0;
		// 接受原先的直行内容描述
		$describe = isset(self::$classbase['@describe']) ? self::$classbase['@describe']: NULL;
		// 删除现有的直行内容描述
		unset(self::$classbase['@describe']);
		// 接受原先的直行内容描述
		$todo = isset(self::$classbase['@todo']) ? self::$classbase['@todo']: NULL;
		// 删除现有的直行内容描述
		unset(self::$classbase['@todo']);
		// 接受内容描述的其他内容信息
		$other = isset(self::$classbase['other']) ? self::$classbase['other']: NULL;
		// 删除内容描述的其他内容信息
		unset(self::$classbase['other']);
		$count = 0;
		// 如果other 不是空则进行循环处理
		if (!$other == null)
		{
			// 处理原数据内容
			foreach ($other as $key => $val)
			{
				foreach ($val as $k => $v)
				{
					// 如果在循环过程中遇到数组下标存为1的直则认为是一个新的开始，进行一次计数作为以后程序判断依据
					if ($k == 1) $count += 1;
					// 如果统计结果为1 那么则认为是顺序结构下的详细描述后续内容，将其存入数组
					if ($count == 1) self::$classbase['@describe'][$k] = $v;
					// 如果统计结果为2 那么则认为是顺序结构下的计划内容的后续内容，将其存入数组
					if ($count == 2) self::$classbase['@todo'][$k] = $v;
				}
			}
			// 将原信息存入现在新的数组中 其中有计划以及描述
			array_unshift(self::$classbase['@describe'],$describe);
			array_unshift(self::$classbase['@todo'],$todo);
		}
	}

	private static function ClassHandleNeedOrStandby()
	{
		foreach (self::$classbase['normal'] as $key => $val)
		{
			if ( in_array($key,self::$setClassAttribute)) self::$classbase['need'][$key] = $val;
			else self::$classbase['standby'][$key] = $val;
		}
		if (!isset(self::$classbase['need']['@title'])) self::$classbase['need']['@title'] = null;
		if (!isset(self::$classbase['need']['@author'])) self::$classbase['need']['@author'] = null;
		if (!isset(self::$classbase['need']['@date'])) self::$classbase['need']['@date'] = null;
		if (!isset(self::$classbase['need']['@version'])) self::$classbase['need']['@version'] = null;
	}

	// 处理class 注释内容信息结果最终的位置
	private static function getClassInfo()
	{
		// 设置需要用到的内容，进行准确处理
		self::ClassHandleFunctionObj();
		// 取出所有需要用到的函数名，进行最终处理
		self::ClassHandleFunctionName();
		// 进入多余内容处理函数，清除不需要的部分数据
		self::ClassHandleSuperfluousContent();
		// 进入前置内容显示处理部分，从新赋值，变更索引数组为关联数组
		self::ClassHandlePreContent();
		// 处理类错误信息内容，使其变的比较正常
		self::ClassHandleContentError();
		// 处理需要显示的详情信息
		self::ClassHandleOther();
		// 处理需要的重要信息与非重要信息
		self::ClassHandleNeedOrStandby();
		// 处理最终显示内容
		return self::ClassHandleShow();
	}

	// 非必要处理 最后想要显示的内容
	private static function ClassHandleShow()
	{
		$data = [];
		$data['classname'] = self::$classbase['classname'];
		$data['classurl'] = self::$classbase['classurl'];
		$data['classfile'] = self::$classbase['classfile'];
		$data['classhttpurl'] = self::$classbase['classhttpurl'];
		$data['classshowname'] = self::$classbase['classshowname'];
		$data['functionname'] = self::$classbase['functionname'];
		$data['error'] = self::$classbase['error'];
		$data['need'] = self::$classbase['need'];
		$data['standby'] = self::$classbase['standby'];
		$data['@describe'] = self::$classbase['@describe'];
		$data['@todo'] = self::$classbase['@todo'];
		return $data;
	}

	// 初始化类配置信息即类文件运行入口处
	public function GetClassDoc($doc)
	{
		// 文件初始化功能
		self::setClass($doc);
		// 文件信息内容处理功能模块
		self::getClassBase($doc);
		// 文件最后出口位置
		return self::getClassInfo();
	}
	#=============================================================================================================

	#=============================================================================================================
	// 设置反射类函数
	private static function setFunction($doc,$fun)
	{
		// 初始化反射类对象
		self::$reffunction = new \ReflectionMethod($doc,$fun);
	}

	// 读取函数的本信息
	private static function getFunctionBase()
	{
		self::$functionbase['functionname'] = self::$reffunction->getName();
		// 读取类内容注释信息
		self::$functionbase['apidoc'] = self::$reffunction->getDocComment().PHP_EOL;
		// 读取该函数的家族成员修饰
		if (self::$reffunction->isPublic()) self::$functionbase['family'] = 'Public';
		// 以下两句基本没用，在调用时候 只判断是否是public即可如果为空则不显示整个api就好
		// 为了避免歧义特别放入
		// elseif (self::$reffunction->isPrivate()) self::$functionbase['family'] = 'Private';
		// elseif (self::$reffunction->isProtected()) self::$functionbase['family'] = 'Protected';
		else self::$functionbase['family'] = null;
		// 静态方法修饰符
		if (self::$reffunction->isStatic()) self::$functionbase['static'] = 'static';
		else self::$functionbase['static'] = null;
		// 最终方法修饰符
		if (self::$reffunction->isFinal()) self::$functionbase['final'] = 'final';
		else self::$functionbase['final'] = null;
	}

	// 处理多余内容显示部分
	private static function FunHandleSuperfluousContent()
	{
		// 定义一个空变量数组
		$data = [];
		// 数组拆分传入的注释内容
		$doc = explode(PHP_EOL, self::$functionbase['apidoc']);
		// 统计拆分后数组长度
		$num = count($doc);
		// 循环内容取出多余并不需要的内容
		foreach ($doc as $key => &$val)
		{
			// 如果数组下标是以下内容则删除该内容
			if (@$key === 0 || @$key === $num || @$key === ($num-1) ||  @$key === ($num-2)) unset($key);
			// 否则将内容存储起来 处理前置内容，清除空格 *符号
			else $data[] = trim(str_replace('*','',$val));
		}
		self::$functionbase['apidoc'] = $data;
		// 如果传入类型是类类型
	}


	// 处理前置内容，从新整理键与值的内容
	private static function FunHandlePreContent()
	{
		// 第一次处理内容提前处理处需要与不需要的内容
		foreach (self::$functionbase['apidoc'] as $key => $val)
		{
			$res = explode(' ',$val);
			if (in_array($res[0],self::$setFunctionAttribute)) self::$functionbase['need'][$res[0]] = $res[1];
			else self::$functionbase['standby'][$res[0]] = $res[1];
		}
		// 第二次处理内容 处理处请求和返回的内容以及函数说明与计划事项
		foreach (self::$functionbase['apidoc'] as $key => $val)
		{
			$res = explode(' ',$val);
			if ($res[0] == '@param') self::$functionbase['param'][][$res[0]] = $val;
			if ($res[0] == '@return') self::$functionbase['return'][][$res[0]] = $val;
			if ($res[0] == '@describe') self::$functionbase['describe'][] = $res[1];
			if ($res[0] == '@todo') self::$functionbase['todo'][] = $res[1];
		}

		// 作为统计数组使用
		$count = 0;
		// 第三次处理内容 处理说明与计划的详细内容部分
		foreach (self::$functionbase['apidoc'] as $key => $val)
		{
			$res = explode(' ',$val);
			if (is_numeric($res[0]) && $res[0] == 1) $count += 1;
			if (is_numeric($res[0]) && $count == 1) self::$functionbase['describe'][] = $res[1];
			if (is_numeric($res[0]) && $count == 2) self::$functionbase['todo'][] = $res[1];
		}
		unset(self::$functionbase['apidoc']);

	}

	// 处理类里面需要显示与展示的内容
	private static function FunHandleNeedOrStandbyContent()
	{
		$list['need'] = self::$functionbase['need'];
		// 建立空数组进行转换
		$data= [];
		// 循环传入内容
		foreach ($list['need'] as $key => $val)
		{
			if ($key == '@describe') unset(self::$functionbase['need'][$key]);
			if ($key == '@todo') unset(self::$functionbase['need'][$key]);
			if ($key == '@return') unset(self::$functionbase['need'][$key]);
			if ($key == '@param') unset(self::$functionbase['need'][$key]);
		}
		if (isset(self::$functionbase['standby']))
		{
			$list['standby'] = self::$functionbase['standby'];
			foreach ($list['standby'] as $key => $val)
			{
				if (is_numeric($key)) unset(self::$functionbase['standby'][$key]);
			}
		}

	}

	// 拆分开请求与返回的内容信息
	private static function FunHandleFunctionParam()
	{
		$param = [];
		$data = [];
		// 如果操作是参数 则单复制给变量list 准备处理
		$list = self::$functionbase['param'];
		// 循环list 处理内部内容并且进行文字分割存入数组data中进行下一步操作使用
		foreach ($list as $key => $val)
		{
			$res = explode(' ',$val['@param']);
			$param[] = $res;
		}
		foreach ($param as $key => $val)
		{
			unset($val[0]);
			$data[$key]['var'] = $val[2];
			$data[$key]['type'] = $val[1];
			$data[$key]['mast'] = $val[3];
			$data[$key]['describe'] = $val[4];
		}
		self::$functionbase['param'] = $data;
	}


	private static function FunHandleFunctionReturn()
	{
		$return = [];
		$data = [];
		// 如果操作是参数 则单复制给变量list 准备处理
		$list = self::$functionbase['return'];
		// 循环list 处理内部内容并且进行文字分割存入数组data中进行下一步操作使用
		foreach ($list as $key => $val)
		{
			$res = explode(' ',$val['@return']);
			$return[] = $res;
		}
		foreach ($return as $key => $val)
		{
			unset($val[0]);
			$data[$key]['type'] = $val[1];
			$json = json_decode($val[2],true);
			if ($json == null) $data[$key]['val'] = $val[2];
			else $data[$key]['val'] = $json;
			$data[$key]['describe'] = $val[3];
		}
		self::$functionbase['return'] = $data;
	}

	private static function FunHandleNeedOrStandby()
	{
		foreach (self::$functionbase['need'] as $key => $val)
		{
			if ( in_array($key,self::$setFunctionAttribute)) self::$functionbase['need'][$key] = $val;
			else self::$functionbase['standby'][$key] = $val;
		}
		if (!isset(self::$functionbase['need']['@title'])) self::$functionbase['need']['@title'] = null;
		if (!isset(self::$functionbase['need']['@author'])) self::$functionbase['need']['@author'] = null;
		if (!isset(self::$functionbase['need']['@date'])) self::$functionbase['need']['@date'] = null;
		if (!isset(self::$functionbase['need']['@version'])) self::$functionbase['need']['@version'] = null;
		if (!isset(self::$functionbase['need']['@api'])) self::$functionbase['need']['@api'] = null;
		if (!isset(self::$functionbase['need']['@request'])) self::$functionbase['need']['@request'] = null;
	}

	// 处理function 注释内容信息结果最终的位置
	private static function getFunctionInfo()
	{
		// 进入多余内容处理函数，清除不需要的部分数据
		self::FunHandleSuperfluousContent();
		// 进入前置内容显示处理部分，从新赋值，变更索引数组为关联数组
		self::FunHandlePreContent();
		// 设置需要用到的内容，进行准确处理
		self::FunHandleNeedOrStandbyContent();
		// 处理函数Api内容
		self::FunHandleFunctionParam();
		// 处理函数Api内容
		self::FunHandleFunctionReturn();
		// 处理最终结果
		self::FunHandleNeedOrStandby();
		// // 处理函数Api内容错误报告die;
		// self::FunHandleFunctionApiError('param');
		// // 处理函数Api内容错误报告
		// self::HandleFunctionApiError('return');
		// 处理最终显示内容
		return self::HandleFunctionShow();
	}

	// 初始化类函数配置信息
	public function GetFunctionDoc($doc,$fun)
	{
		self::setFunction($doc,$fun);
		self::getFunctionBase();
		return self::getFunctionInfo();
	}

	// 非必要处理 最后想要显示的内容
	private static function HandleFunctionShow()
	{
		$data = [];
		$data['functionname'] = isset(self::$functionbase['functionname']) ? self::$functionbase['functionname']:null;
		$data['family'] = isset(self::$functionbase['family']) ? self::$functionbase['family']:null;
		$data['static'] = isset(self::$functionbase['static']) ? self::$functionbase['static']:null;
		$data['final'] = isset(self::$functionbase['final']) ? self::$functionbase['final']:null;
		$data['error'] = isset(self::$functionbase['error']) ? self::$functionbase['error']:null;
		$data['need'] = isset(self::$functionbase['need']) ? self::$functionbase['need']:null;
		$data['standby'] = isset(self::$functionbase['standby']) ? self::$functionbase['standby']:null;
		$data['param'] = isset(self::$functionbase['param']) ? self::$functionbase['param']:null;
		$data['return'] = isset(self::$functionbase['return']) ? self::$functionbase['return']:null;
		$data['describe'] = isset(self::$functionbase['describe']) ? self::$functionbase['describe']:null;
		$data['todo'] = isset(self::$functionbase['todo']) ? self::$functionbase['todo']:null;
		return $data;
	}
}
?>
