<?php
$stationName = $_GET["station_name"];
$trainCode = $_GET["train_code"];
$stationEncode = $_GET["station_encode"];
$punctualArrivalTime = $_GET["punctual_arrival_time"];
$dateString = date_format(date_create(), "Y-m-d");
$timeStamp = floor(microtime(true) * 1000);
if ($stationName && $trainCode && $stationEncode && $punctualArrivalTime) {
	$f3 = fopen("http://dynamic.12306.cn/mapping/kfxt/zwdcx/LCZWD/cx.jsp?cz=".$stationName."&cc=".$trainCode."&cxlx=0&rq=".$dateString."&czEn=".$stationEncode."&tp=".$timeStamp, "r");
	if ($f3) {
		$content = stream_get_contents($f3);
		if ($content == null) echo("暂无(点击刷新)");
		else if (strlen($content) == 2042) { // 此时源信息为“网络可能存在问题，请您重试一下！”
			echo("暂无(点击刷新)");
		}
		// 由于本php文件为UTF-8编码，而从服务器端返回的字符串为gb2312编码，所以必须使用iconv函数进行编码转换！
		else echo(dispResult(iconv("gb2312", "UTF-8", $content), $punctualArrivalTime));
	}
	else echo("暂无(点击刷新)");
	fclose($f3);
}
else echo("参数错误！");
function dispResult($cont, $punctualTime) { // 解析正晚点信息
	if (gettype($cont) == "string") {
		if (strpos($cont, "尚未开通")) { // 此时源信息为“目前XX站尚未开通列车正晚点服务”
			return "该站未开通正晚点查询服务";
		}
		else if (strpos($cont, "暂无")) { // 此时源信息为“目前暂无XX次列车XX站到达信息，请稍后重新查询”
			return "暂无(点击刷新)";
		}
		else if (strpos($cont, "预计")) { // 此时源信息为“预计XX次列车，XX站到达时间为XX:XX”
			return "预计".substr($cont, -6, 5)."到";
		}
		else if (strpos($cont, "的时间")) { // 此时源信息为“XX次列车，到达XX站的时间为XX:XX”
			if (substr($cont, -6, 5) == $punctualTime) return substr($cont, -6, 5)."到，正点";
			else return substr($cont, -6, 5)."到";
		}
		else if (strpos($cont, "的信息")) { // 此时源信息为“列车时刻表中无XX次列车的信息”
			return "无当日该车次信息";
		}
		else return "暂无(点击刷新)";
	}
	else return "Unknown Error!";
}
?>