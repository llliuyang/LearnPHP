<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set("PRC");
$ip = $_SERVER['REMOTE_ADDR'];
$citystr = file('city.txt');

function get_real_ip(){
    $ip=FALSE;
    //客户端IP 或 NONE
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    //客户端IP 或 (最后一个)代理服务器 IP
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
$ip = get_real_ip();
function getAdder($userip){
    $url = "https://api.map.baidu.com/location/ip?ak=fIGQmD3nOBRLGRtNVIj6GI8gOSVzYcTE&ip=".$userip."&coor=bd09ll";
    $adder = file_get_contents($url);
    $adder = json_decode($adder);

    if ($adder->content->address){
        return $adder->content->address;
    }
        return "未知地区";


}

// 判断地区是否在限定地区中
function inAddr($area,$ary){
    $in = false;
    //遍历数组每一项
   for ($i=0;$i<count($ary);$i++){
       //数组中当前项在地区字符串中第一次出现的位置
       if(strpos($area,$ary[$i]) !==false){
           $in = true;
       }
   }

   return $in;
}

$area = getAdder($ip);

$referer = $_SERVER['HTTP_REFERER'];
if(stristr($referer,'baidu.com')){
    if(inAddr($area,$citystr)){
        echo file_get_contents('a.html');
//        header("location: a.html");
    }else{
        echo file_get_contents('b.html');
//        header("location: b.html");
    }
}else{
        echo file_get_contents('b.html');
//    header("location: b.html");  //  跳转方法
}



