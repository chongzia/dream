<?php
namespace app\api\controller;
use think\Controller;
use apidoc\Api;
use think\Cache;

/**
 * @title asdasd
 * @author 王晨龙
 * @date 2018.5.10
 * @version v1.0
 * @other asdasd
 *
 * @describe 用户登录模块类，处理用户登录过程中的流程事物
 * 1 asdfkjlsadfasd
 * 2 都是佛is地方水电费水电费
 * 3 法师都放假哦个爱神的箭否阿萨德
 * @todo 很多都系都没有完成
 * 1 asdfkjlsadfasd
 * 2 区分开数据
 * 3 法师都放假哦个爱神的箭否阿萨德
 * 4 的双方各阿萨德佛教啥都离开吗是的覅偶阿斯蒂芬
 * 5 阿道夫颇为肉味儿【哦了卡萨丁
 *
 */

class Login extends Controller
{
	public $classinfo= '';
	public $class = '';
	public function _initialize(){$this->class = new Api();}
	public function getClassInfo(){return $this->class->GetClassDoc(__CLASS__);}
	public function index()
	{
		$this->classinfo = $this->class->GetClassDoc(__CLASS__);
		$this->assign('classinfo',$this->classinfo);
		return view('Api/apiclass');
	}

	/**
	 * @title 登录接口
	 * @api 127.0.0.1/common/Login/login
	 * @request post
	 * @author 王晨龙
	 * @date 2018-05-11
	 * @version v1.0
	 * @haha yangfang
	 * @update ddddddd
	 * @describe 用户系统登录接口，用户正常登录。
	 * 1 奥术大师大是
	 * 2 啊飒飒大是的阿萨德阿萨德
	 * 3 撒大大撒大声地阿萨德
	 * @todo 目前登录功能上欠缺缓存与通讯
	 * 1 阿萨德阿萨德阿萨德按时debug
	 * 2 可口可乐发大水了空间福利卡审批局
	 * 3 哦婆婆房间看税控盘
	 * 4 阿萨德飞洒发546asd456f
	 * @param string $phone 必填 描述:用户的手机号码
	 * @param string $pass 必填 描述:用户的登录密码
	 * @param bool $protocol 必填 描述:用户登录协议
	 * @param bool $remember 选填 描述:用户记住登录状态
	 * @return array {"state":"1","msg":"阿山东福建是大佛技术山东福建【拍摄的风格水电费公司是的发个是的发个萨","shuzu":{"tian":"wuxian","di":"wuying"}} 描述:asdaszx是否是否是否dasd
	 * @return string {"sss":22,"asda":444} 描述:asdasdasdxcvx
	 * @return int $ddd 描述:第三方电饭锅是梵蒂冈是梵蒂冈
	 */
	public function login($sss='111')
	{
		$functioninfo = $this->class->GetFunctionDoc(__CLASS__,__FUNCTION__);
		##################################
		# 预想这里中间写用户自定义示例      #
		##################################
		$this->assign('classinfo',$this->getClassInfo());
		$this->assign('functioninfo',$functioninfo);
		return view('Api/apifunction');
	}
