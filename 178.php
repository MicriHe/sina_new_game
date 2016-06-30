<?php
include_once "Conn.php";
header("Content-type: text/html; charset=utf-8");

$index = 0;
$suffx = 0;
ignore_user_abort(); // 后台运行
set_time_limit(0); // 取消脚本运行时间的超时上限
while(true)
{
	$page = "";
	$suffx = $index * 12;
    if($suffx != 0)
    {
        $page = "{$suffx}";
    }
	else
	{
		$page = "";
	}
	$ch= curl_init();
	curl_setopt($ch,CURLOPT_URL,"http://top.sina.com.cn/news/show/news/iphone/".$page);
	//echo "http://top.sina.com.cn/news/show/news/iphone/".$page;
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	if($http_code != 200)
	{
		echo "get info error";
		break;
	}
	curl_close($ch);
	include_once "simple_html_dom.php";
	$html = str_get_html($data) or die("html dom get error");
	$ret = $html->find("div.CONTENTWP")[0]->childNodes(2)->find("ul")[0]->find("li");
	$encode_arr=array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS', 'eucjp-win','sjis-win','EUC-JP');
	foreach ($ret as $item)
	{
		$title =  $item->find("span.booklink")[0]->plaintext;
		$content =  $item->find("p")[0]->plaintext;
		$url =  $item->find("a.f16&.tt&.blink")[0]->href;
		$img = $item->find("img.pic")[0]->src;
		$date = $item->find("span.fr&.time")[0]->plaintext;
		//$sql = "insert into sina_new_game values('".$title."','".$content."','".$url."','".$img."','".$date."')";
		$sql = "insert into sina_new_game VALUES ('{$title}','{$content}','{$url}','{$img}','{$date}')";
		//echo  mb_detect_encoding($title, $encode_arr);
		echo $sql;
		echo  "<br>";
		//mysqli_query($con,$sql) or die("insert error".mysqli_error($con));
		if(!mysqli_query($con,$sql))
		{
			continue;
		}
	}
	$index = $index + 1;
}
mysqli_close($con);

?>