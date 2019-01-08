<?php

$Url = "http://192.168.230.129/hello.php";
$Url_Cookie = "PHPSESSID=4av01iesst8l2vk2rkj157q7f7; security_level=0";
#$array = 'lastname,email,phone';
switch ($_SERVER['REQUEST_METHOD'])
{
case "GET":
	if ($_SERVER['QUERY_STRING'] == "readme"){
		echo <<<EOT
		<!DOCTYPE HTML>
		<html>
			<head>
				<meta charset='UTF-8'>
				<title>PHP中转站</title>
			</head>
			<body>
				<h2>欢迎使用PHP中转站
				使用说明:
					<h3>&nbsp&nbsp&nbsp&nbsp使用前需要修改该文件的$.Url(第三行)为需要中转网站地址</h3>
					<h3>&nbsp&nbsp&nbsp&nbsp如果需要登陆可以修改该文件的$.Url_Cookie(第四行)为中转站的Cookie</h3>
					<h3>&nbsp&nbsp&nbsp&nbspGET请求直接在请求网站后加参数(http://192.168.230.132/1.php?z=123&y=321)</h3>
					<h3>&nbsp&nbsp&nbsp&nbspPOST请求直接在请求网站后加POST参数</h3>
			</body>
		</html>
EOT;
	}else{
		$Url_Behind = $_SERVER['QUERY_STRING']; // 获取网址参数
		$Request_Url = $Url.'?'.$Url_Behind;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Request_Url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。详细:https://www.cnblogs.com/you-jia/p/4118659.html
		curl_setopt($ch, CURLOPT_HEADER, 0);// 不获取响应头信息
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch , CURLOPT_COOKIE , $Url_Cookie);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15); 
		$output = curl_exec($ch);
		curl_close($ch);
		echo $output;
	}
	break;
case "POST":
	$inpu = file_get_contents("php://input");
	$input = urldecode($inpu);
		$Post_data = array(
		);
	$Post_Datas = explode('&',$input);
	$Post_count = count($Post_Datas);
	for ($i=0; $i<$Post_count; $i++){
		$Post_Data_content = explode('=',$Post_Datas[$i]);   //这里有,如果传输and 1=1 这样就会匹配带参数名3个数组了
		$Post_Data_content_content = explode($Post_Data_content[0].'=',$Post_Datas[$i]);   //解决如果参数值里面带'='会匹配到3个数值
		$Post_data[$Post_Data_content[0]] = $Post_Data_content_content[1];
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $Url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。详细:https://www.cnblogs.com/you-jia/p/4118659.html
	curl_setopt($ch, CURLOPT_HEADER, 0);// 不获取响应头信息
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch , CURLOPT_COOKIE , $Url_Cookie);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15); 
	curl_setopt($ch, CURLOPT_POST, 1); // post 提交方式
	curl_setopt($ch, CURLOPT_POSTFIELDS, $Post_data); //post数据 
	$output = curl_exec($ch);
	curl_close($ch);
	echo $output;
}
?>
