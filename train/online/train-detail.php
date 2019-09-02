<?php
$trainCode = $_GET["train_code"];
$trainNo = $_GET["train_no"];
$fromStationCode = $_GET["from_station_code"];
$toStationCode = $_GET["to_station_code"];
$dateString = $_GET["date_string"];
$errorInfo1 = "
	<h2 style='font-family:Microsoft YaHei;color:#E01'>HTTP 400 : Bad Request</h2>
	<h2 style='font-family:Arial;font-weight:bold'>Detail : Parameter Error!</h2>";
$errorInfo2 = "
	<h2 style='font-family:Microsoft YaHei;color:#E01;font-weight:bold'>查询失败，请检查网络或服务器后重试！</h2>";
if (strlen($trainNo) == 12 && strlen($fromStationCode) == 3 && strlen($toStationCode) == 3 && strlen($dateString) == 10) {
	$f2 = fopen("https://kyfw.12306.cn/otn/czxx/queryByTrainNo?train_no=".$trainNo."&from_station_telecode=".$fromStationCode."&to_station_telecode=".$toStationCode."&depart_date=".$dateString, "r");
	//echo "https://kyfw.12306.cn/otn/czxx/queryByTrainNo?train_no=".$trainNo."&from_station_telecode=".$fromStationCode."&to_station_telecode=".$toStationCode."&depart_date=".$dateString;
	/*
	https://kyfw.12306.cn/otn/czxx/queryByTrainNo?train_no=".$trainNo."&from_station_telecode=".$fromStationCode."&to_station_telecode=".$toStationCode."&depart_date=".$dateString
	*/
	if ($f2) {
		$jsonContent = stream_get_contents($f2);
		if ($jsonContent == "null") echo($errorInfo1);
		else {
			$trainDetail = json_decode($jsonContent, true);
			echo(file_get_contents("https://kyfw.12306.cn/otn/czxx/queryByTrainNo?train_no=".$trainNo."&from_station_telecode=".$fromStationCode."&to_station_telecode=".$toStationCode."&depart_date=".$dateString));
			$normalResp = "
			<html>
			<head><title>".$trainCode."次（".$trainDetail["data"]["data"][0]["start_station_name"]."—".$trainDetail["data"]["data"][0]["end_station_name"]."）| ".$trainDetail["data"]["data"][0]["train_class_name"]."</title>
			</head>
			<style type='text/css'>
			tr.result-set {
				background:linear-gradient(#3CB46E, #1E9650, #007832);
				color:#FFF;
				line-height:40px;
			}
			td {
				text-align:center;
			}
			table#table1 {
				font-size:17.1px;
				position:absolute;
				left:0px;
				top:-1px;
			}
			</style>
			<script src='js/query-by-ajax2.js'></script>
			<script type='text/javascript'>
			function highlightTd(tdObj) {
				tdObj.style.background = 'linear-gradient(#5AD28C, #3CB46E, #1E9650)';
				tdObj.style.color='#FFF';
			}
			function returnTdNormalStyle(tdObj) {
				tdObj.style.background = 'linear-gradient(#3CB46E, #1E9650, #007832)';
				tdObj.style.color='#FFF';
			}
			function reverseColor(tdObj) {
				tdObj.style.background = 'linear-gradient(#007832, #1E9650, #3CB46E)';
				tdObj.style.color='#FFF';
			}
			function displayDelayInfo(index) {
				document.getElementById('delay_or_normal_' + index).innerHTML = '查询中，请稍候……';
				showDelayInfo(index, '".$trainCode."');
			}
			</script>
			<body style='font-family:Microsoft YaHei;font-size:20px'>
			<table id='table1' border='0' cellpadding='1' cellspacing='1'>";
			for ($i = 0; $i < count($trainDetail["data"]["data"]); $i++) {
				$onclickStr = ($i == 0) ? ">始发站无正晚点信息" : "' onclick='displayDelayInfo(".($i + 1).")'>点击查询";
				$normalResp = $normalResp."
				<tr class='result-set'>
				<td id='num_".($i + 1)."' style='width:50px'>".($i + 1)."</td>
				<td id='station_name_".($i + 1)."' style='width:120px'>".$trainDetail["data"]["data"][$i]["station_name"]."</td>
				<td id='arrive_time_".($i + 1)."' style='width:90px'>".TimeDisplay($trainDetail["data"]["data"], $i, "arrival", $trainDetail["data"]["data"][$i]["arrive_time"])."</td>
				<td id='start_time_".($i + 1)."' style='width:90px'>".TimeDisplay($trainDetail["data"]["data"], $i, "departure", $trainDetail["data"]["data"][$i]["start_time"])."</td>
				<td id='stopover_time_".($i + 1)."' style='width:100px'>".$trainDetail["data"]["data"][$i]["stopover_time"]."</td>
				<td id='delay_or_normal_".($i + 1)."' onmouseover='highlightTd(this)' onmouseout='returnTdNormalStyle(this)' onmousedown='reverseColor(this)' onmouseup='returnTdNormalStyle(this)' style='width:270px'".$onclickStr."</td>
				</tr>";
			}
			$normalResp = $normalResp."</table></body></html>";
			echo($normalResp);
		}
	}
	else echo($errorInfo2);
	fclose($f2);
}
else echo($errorInfo1);
function TimeDisplay($trainInfoArray, $stationIndex, $option, $timeString) {
	if ($option == "arrival") {
		if ($stationIndex == 0) return "--:--";
		else return $timeString;
	}
	else if ($option == "departure") {
		if ($stationIndex == count($trainInfoArray) - 1) return "--:--";
		else return $timeString;
	}
	else return false;
}
?>