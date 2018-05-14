<?php
//把unicode转化成中文
function decodeUnicode($str)
{
    return preg_replace_callback
    (
        '/\\\\u([0-9a-f]{4})/i',
        create_function
        (
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str
    );
}

// 格式化json数据
function format_json($arr, $html = true)
{
    // 预处理数组数据，将数组数据进行初次矫正
    $json = json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    // 统计循环次数初始化
    $tabcount = 0;
    // 结果值初始化
    $result = '';
    // 引号控制开关
    $inquote = false;
    // 匹配循环条件控制开关
    $ignorenext = false;
    // 采用格式方案默认html
    if ($html)
    {
        // 空格递进
        $tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        // 回车换行
        $newline = "<br/>";
    }
    else
    {
        // 空格递进
        $tab = "\t";
        // 回车换行
        $newline = "\n";
    }
    // 循环整个json长度
    for($i = 0; $i < strlen($json); $i++)
    {
        // 为每一个字符串单个进行匹配
        $char = $json[$i];
        // 如果默认为false 关闭状态
        if ($ignorenext)
        {
            // 结赋值
            $result .= $char;
            // 继续默认
            $ignorenext = false;
        }
        else
        {
            // 匹配方案
            switch($char)
            {
                // 左花括号处理
                case '{':
                    // 初始值++
                    $tabcount++;
                    // 如果初始值大于第一次 瓶装所有结果加上替换次数
                    if ($tabcount > 1) $result .= $newline . $tab . $char . $newline . str_repeat($tab, $tabcount);
                    // 否则使用固定格式
                    else $result .= $newline . $char . $newline . str_repeat($tab, $tabcount);
                break;
                // 右花括号处理
                case '}':
                // 初始值--
                    $tabcount--;
                    // 取出结果的空格瓶装所有信息
                    $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
                break;
                // 单引号方案
                case ',':
                    // 替换字符串瓶装字符串取得结果
                    $result .= $char . $newline . str_repeat($tab, $tabcount);
                break;
                // 双引号方案
                case '"':
                    // 双引号如果是真的时候
                    $inquote = !$inquote;
                    // 直接得到结果
                    $result .= $char;
                break;
                case '\\':
                    // 对文章内字符处理替换时候的规则，将结果变为true进行如果里面的操作，重置下一个字符
                    if ($inquote) $ignorenext = true;
                    // 得到结果返回
                    $result .= $char;
                break;
                default:
                // 默认得到返回结果
                    $result .= $char;
            }
        }
    }
    return $result;
  }
