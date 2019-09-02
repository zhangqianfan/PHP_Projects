<?php
header("Content-Type:text/html;charset=utf-8");
include 'station-dict.php';
$fromStation = $_GET["from"];
$toStation = $_GET["to"];
$dateString = $_GET["date"];
$errorResp = "<h2 align='center' style='color:#FA0006'>查询失败，请检查网络或服务器后重试或切换到离线版查询！</h2>";
$normalResp = "<table id='results-content' border='1' cellpadding='1' cellspacing='0' width='1200px' align='center' style='background:#C8D7E6;border:1px solid #FFF'>";
if ($fromStation && $toStation && $dateString) {
	$reqUrl = "https://kyfw.12306.cn/otn/leftTicket/query?leftTicketDTO.train_date=".$dateString."&leftTicketDTO.from_station=".$fromStation."&leftTicketDTO.to_station=".$toStation."&purpose_codes=ADULT";
	$f1 = fopen($reqUrl, "r");
	if ($f1) {
		$jsonStr = stream_get_contents($f1);
		$trainDataStr = json_decode($jsonStr, true);
		/* 
		if (count($trainData["data"]) > 0) {
			for ($i = 0; $i < count($trainData["data"]); $i++) {
				if ($trainData["data"][$i]["queryLeftNewDTO"]["lishi"] == "99:59") continue;
				else {
					$normalResp = $normalResp."
					<tr class='".trainType($trainData["data"][$i]["queryLeftNewDTO"]["station_train_code"])."' style='line-height:45px' onmouseover='showTrBgColor(this, 180, 195, 210)' onmouseout='showTrBgColor(this, 200, 215, 230)'>
					<td align='center' style='width:84px;font-family:Arial;font-size:27px;font-weight:bold'>
					<a id='".$trainData["data"][$i]["queryLeftNewDTO"]["station_train_code"]."|".$trainData["data"][$i]["queryLeftNewDTO"]["train_no"]."|".$fromStation."|".$toStation."|".$dateString."|".$trainData["data"][$i]["queryLeftNewDTO"]["start_station_telecode"]."|".$trainData["data"][$i]["queryLeftNewDTO"]["end_station_telecode"]."' title='点击查看经停站信息' href='javascript:;' class='train_no_disp' onclick='viewTrainDetail(this)' style='color:#963296' onmouseover='setTextColor(this, 0, 0, 255)' onmouseout='setTextColor(this, 150, 50, 150)' onclick='setTextColor(this, 0, 0, 255)'>".$trainData["data"][$i]["queryLeftNewDTO"]["station_train_code"]."</a></td>
					<td class='daofaxinxi' style='width:240px'>
					<table align='center'><tr valign='middle' style='line-height:30px'>
					<td align='center'>".fromStationDisp($trainData["data"][$i]["queryLeftNewDTO"]["from_station_name"], $trainData["data"][$i]["queryLeftNewDTO"]["start_station_name"])."<br><span class='chufashijian' style='font-family:Arial;font-size:22px'>".$trainData["data"][$i]["queryLeftNewDTO"]["start_time"]."</span></td>
					<td align='center'>—></td>
					<td align='center'>".toStationDisp($trainData["data"][$i]["queryLeftNewDTO"]["to_station_name"], $trainData["data"][$i]["queryLeftNewDTO"]["end_station_name"])."<br><span class='daodashijian' style='font-family:Arial;font-size:22px'>".$trainData["data"][$i]["queryLeftNewDTO"]["arrive_time"]."</span></td>
					</tr></table></td>
					<td class='lishi' style='width:60px'>".$trainData["data"][$i]["queryLeftNewDTO"]["lishi"]."</td>
					<td class='shifazhan' style='width:100px'>".$trainData["data"][$i]["queryLeftNewDTO"]["start_station_name"]."</td>
					<td class='zhongdaozhan' style='width:100px'>".$trainData["data"][$i]["queryLeftNewDTO"]["end_station_name"]."</td>
					<td class='shangwuzuo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["swz_num"])."</td>
					<td class='tedengzuo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["tz_num"])."</td>
					<td class='yidengzuo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["zy_num"])."</td>
					<td class='erdengzuo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["ze_num"])."</td>
					<td class='gaojiruanwo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["gr_num"])."</td>
					<td class='ruanzuo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["rz_num"])."</td>
					<td class='ruanwo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["rw_num"])."</td>
					<td class='yingwo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["yw_num"])."</td>
					<td class='yingzuo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["yz_num"])."</td>
					<td class='wuzuo' style='width:60px'>".dispSeatsNum($trainData["data"][$i]["queryLeftNewDTO"]["wz_num"])."</td></tr>";
				}
			}
			$normalResp = $normalResp."</table>";
			echo($normalResp);
		} */
		$trainData = array();
		if (count($trainDataStr["data"]["result"]) > 0) {
			for ($ind = 0; $ind < count($trainDataStr["data"]["result"]); $ind++) {
				$trainData[$ind] = explode("|", $trainDataStr["data"]["result"][$ind]);
			}
			for ($i = 0; $i < count($trainData); $i++) {
				if ($trainData[$i][10] == "99:59") continue;
				else {
					$normalResp = $normalResp."
					<tr class='".trainType($trainData[$i][3])."' style='line-height:30px' onmouseover='showTrBgColor(this, 180, 195, 210)' onmouseout='showTrBgColor(this, 200, 215, 230)'>
					<td align='center' style='width:94px;font-family:Arial;font-size:27px;font-weight:bold'>
					<a id='".$trainData[$i][3]."|".$trainData[$i][2]."|".$trainData[$i][6]."|".$trainData[$i][7]."|".dateStringFormat($trainData[$i][13])."|".$trainData[$i][4]."|".$trainData[$i][5]."' title='点击查看经停站信息' href='javascript:;' class='train_no_disp' onclick='viewTrainDetail(this)' style='color:#963296' onmouseover='setTextColor(this, 0, 0, 255)' onmouseout='setTextColor(this, 150, 50, 150)' onclick='setTextColor(this, 0, 0, 255)'>".$trainData[$i][3]."</a></td>
					<td class='daofaxinxi' style='width:240px'>
					<table align='center'><tr valign='middle' style='line-height:30px'>
					<td align='center'>".fromStationDisp(stationCodeToName($trainData[$i][6]), stationCodeToName($trainData[$i][4]))."<br><span class='chufashijian' style='font-family:Arial;font-size:22px'>".$trainData[$i][8]."</span></td>
					<td align='center'>—></td>
					<td align='center'>".toStationDisp(stationCodeToName($trainData[$i][7]), stationCodeToName($trainData[$i][5]))."<br><span class='daodashijian' style='font-family:Arial;font-size:22px'>".$trainData[$i][9]."</span></td>
					</tr></table></td>
					<td class='lishi' style='width:60px'>".$trainData[$i][10]."</td>
					<td class='yunxingqujian' style='width:120px'>始：".stationCodeToName($trainData[$i][4])."<br>终：".stationCodeToName($trainData[$i][5])."</td>
					<td class='shangwuzuo' style='width:67px'>".dispSeatsNum($trainData[$i][32])."</td>
					<td class='tedengzuo' style='width:67px'>".dispSeatsNum($trainData[$i][25])."</td>
					<td class='yidengzuo' style='width:67px'>".dispSeatsNum($trainData[$i][31])."</td>
					<td class='erdengzuo' style='width:67px'>".dispSeatsNum($trainData[$i][30])."</td>
					<td class='gaojiruanwo' style='width:67px'>".dispSeatsNum($trainData[$i][21])."</td>
					<td class='ruanzuo' style='width:67px'>".dispSeatsNum($trainData[$i][24])."</td>
					<td class='ruanwo' style='width:67px'>".dispSeatsNum($trainData[$i][23])."</td>
					<td class='yingwo' style='width:67px'>".dispSeatsNum($trainData[$i][28])."</td>
					<td class='yingzuo' style='width:67px'>".dispSeatsNum($trainData[$i][29])."</td>
					<td class='wuzuo' style='width:67px'>".dispSeatsNum($trainData[$i][26])."</td></tr>";
				}
			}
			$normalResp = $normalResp."</table>";
			echo($normalResp);
		}
		//else echo("<h2 align='center' style='color:#66F'>未查询到车次信息，请更改查询日期或尝试中转查询。</h2>");
		else echo($jsonStr);
	}
	else echo($errorResp);
	fclose($f1);
}
else echo($errorResp);
function trainType($trainNoStr) {
	$res = "unknown";
	if (gettype($trainNoStr) == "string") {
		switch (substr($trainNoStr, 0, 1)) {
			case "K":
				$res = "kuaisu"; break;
			case "T":
				$res = "tekuai"; break;
			case "Z":
				$res = "zhida"; break;
			case "G":
				$res = "gaotie"; break;
			case "D":
				$res = "dongche"; break;
			case "C":
				$res = "chengji"; break;
			case "Y":
				$res = "lvyou"; break;
			case "L":
				$res = "linke"; break;
			default:
				$res = "pukuai"; break;
		}
		return $res;
	}
	return $res;
}
function dispSeatsNum($seatsNum) {
	if ($seatsNum == "有") {
		$displayStr = ">20张";
	}
	else if ($seatsNum == "无") $displayStr = $seatsNum;
	else if ($seatsNum == "") $displayStr = "--";
	else if ($seatsNum == "*") $displayStr = "未起售";
	else $displayStr = $seatsNum."张";
	return $displayStr;
}
function fromStationDisp($fromSta, $startSta) {
	if (gettype($fromSta) == "string" && gettype($startSta) == "string") {
		if ($fromSta == $startSta) {
			$displayStr = "<span class='chufazhan' style='font-family:黑体;font-size:18px;color:#0006FA'>".$fromSta."</span>";
		}
		else $displayStr = "<span class='chufazhan' style='font-family:黑体;font-size:18px'>".$fromSta."</span>";
	}
	else $displayStr = "null";
	return $displayStr;
}
function toStationDisp($toSta, $endSta) {
	if (gettype($toSta) == "string" && gettype($endSta) == "string") {
		if ($toSta == $endSta) {
			$displayStr = "<span class='mudizhan' style='font-family:黑体;font-size:18px;color:#0AB432'>".$toSta."</span>";
		}
		else $displayStr = "<span class='mudizhan' style='font-family:黑体;font-size:18px'>".$toSta."</span>";
	}
	else $displayStr = "null";
	return $displayStr;
}
function dateStringFormat($dateString) {
	if (gettype($dateString) == "string" && strlen($dateString) == 8) {
		$yearStr = substr($dateString, 0, 4);
		$monthStr = substr($dateString, 4, 2);
		$dayStr = substr($dateString, 6, 2);
		$resStr = $yearStr."-".$monthStr."-".$dayStr;
		return $resStr;
	}
	else return "null";
}
?>