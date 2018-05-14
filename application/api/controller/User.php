<?php
namespace app\api\controller\common;
/**
 * @title 用户所有接口
 */
class User 
{
	/**
     * @title 获取文章列表
     * @desc  {"0":"接口地址：http://open.opqnext.com/index.php?c=article&a=index","1":"请求方式：GET","2":"接口备注：必须传入keys值用于通过加密验证"}
     * @param {"name":"page","type":"int","required":true,"default":"1","desc":"页数"}
     * @param {"name":"keys","type":"string","required":true,"default":"xxx","desc":"加密字符串,substr(md5(\"约定秘钥\".$page),8,16)"}
     * @param {"name":"word","type":"string","required":false,"default":"null","desc":"搜索关键字"}
     * @param {"name":"cate","type":"int","required":false,"default":0,"desc":"分类ID,不传表示所有分类"}
     * @param {"name":"size","type":"int","required":false,"default":5,"desc":"每页显示条数，默认为5"}
     * @return {"name":"status","type":"int","required":true,"desc":"返回码：1成功,0失败","level":1}
     * @return {"name":"message","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     * @return {"name":"id","type":"string","required":true,"desc":"文章ID(22位字符串)","level":2}
     * @return {"name":"title","type":"string","required":true,"desc":"文章标题","level":2}
     * @return {"name":"thumb","type":"string","required":true,"desc":"文章列表图","level":2}
     * @return {"name":"content","type":"text","required":true,"desc":"文章内容","level":2}
     * @return {"name":"cate","type":"int","required":true,"desc":"文章分类","level":2}
     * @return {"name":"tags","type":"array","required":true,"desc":"文章标签","level":2}
     * @return {"name":"id","type":"string","required":true,"desc":"标签ID","level":3}
     * @return {"name":"tag","type":"string","required":true,"desc":"标签名称","level":3}
     * @return {"name":"count","type":"int","required":true,"desc":"标签使用数","level":3}
     * @return {"name":"img","type":"array","required":true,"desc":"文章组图","level":2}
     */
	public function index()
	{

	}

	/**
     * @title 获取文章列表
     * @desc  {"0":"接口地址：http://open.opqnext.com/index.php?c=article&a=index","1":"请求方式：GET","2":"接口备注：必须传入keys值用于通过加密验证"}
     * @param {"name":"page","type":"int","required":true,"default":"1","desc":"页数"}
     * @param {"name":"keys","type":"string","required":true,"default":"xxx","desc":"加密字符串,substr(md5(\"约定秘钥\".$page),8,16)"}
     * @param {"name":"word","type":"string","required":false,"default":"null","desc":"搜索关键字"}
     * @param {"name":"cate","type":"int","required":false,"default":0,"desc":"分类ID,不传表示所有分类"}
     * @param {"name":"size","type":"int","required":false,"default":5,"desc":"每页显示条数，默认为5"}
     * @return {"name":"status","type":"int","required":true,"desc":"返回码：1成功,0失败","level":1}
     * @return {"name":"message","type":"string","required":true,"desc":"返回信息","level":1}
     * @return {"name":"data","type":"array","required":true,"desc":"返回数据","level":1}
     * @return {"name":"id","type":"string","required":true,"desc":"文章ID(22位字符串)","level":2}
     * @return {"name":"title","type":"string","required":true,"desc":"文章标题","level":2}
     * @return {"name":"thumb","type":"string","required":true,"desc":"文章列表图","level":2}
     * @return {"name":"content","type":"text","required":true,"desc":"文章内容","level":2}
     * @return {"name":"cate","type":"int","required":true,"desc":"文章分类","level":2}
     * @return {"name":"tags","type":"array","required":true,"desc":"文章标签","level":2}
     * @return {"name":"id","type":"string","required":true,"desc":"标签ID","level":3}
     * @return {"name":"tag","type":"string","required":true,"desc":"标签名称","level":3}
     * @return {"name":"count","type":"int","required":true,"desc":"标签使用数","level":3}
     * @return {"name":"img","type":"array","required":true,"desc":"文章组图","level":2}
     */
	public function doLogin()
	{
		
	}
}