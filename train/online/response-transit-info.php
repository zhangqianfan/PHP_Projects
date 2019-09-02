<?php
header("Content-Type:text/html;charset=utf-8");
// 始发站、中转站、目的站站名
$fromStationName = $_GET["from"];
$transferStationName = $_GET["transfer"];
$toStationName = $_GET["to"];
// 始发站、中转站、目的站代码
$fromStationCode = $_GET["from_station_code"];
$transferStationCode = $_GET["transfer_station_code"];
$toStationCode = $_GET["to_station_code"];
// 日期字符串（yyyy-mm-dd）
$dateString = $_GET["date"];
// 三种错误响应信息
$errorResp1 = "<h2 align='center' style='color:#FA0006'>查询失败，请检查网络或服务器后重试或切换到离线版查询！</h2>";
$errorResp2 = "<h2 align='center' style='color:#66F'>未查询到车次信息，请更改查询日期或站名后再查询。</h2>";
$errorResp3 = ""; // 下述第137~144行代码会有详细说明
if ($fromStationName && $fromStationCode && $transferStationName && $transferStationCode && $toStationName && $toStationCode && $dateString) {
	$reqUrl1 = "https://kyfw.12306.cn/otn/leftTicket/query?leftTicketDTO.train_date=".$dateString."&leftTicketDTO.from_station=".$fromStationCode."&leftTicketDTO.to_station=".$transferStationCode."&purpose_codes=ADULT";
	$reqUrl2 = "https://kyfw.12306.cn/otn/leftTicket/query?leftTicketDTO.train_date=".$dateString."&leftTicketDTO.from_station=".$transferStationCode."&leftTicketDTO.to_station=".$toStationCode."&purpose_codes=ADULT";
	$f6 = fopen($reqUrl1, "r");
	$f7 = fopen($reqUrl2, "r");
	if ($f6 && $f7) {
		$jsonStr1 = stream_get_contents($f6);
		$jsonStr2 = stream_get_contents($f7);
		$trainDataArr1 = json_decode($jsonStr1, true);
		$trainDataArr2 = json_decode($jsonStr2, true);
		if (count($trainDataArr1["data"]["result"]) > 0 && count($trainDataArr2["data"]["result"]) > 0) {
			$cnt1 = 0; $cnt2 = 0;
			// 第一个表格的HTML代码表示的表头
			$tableContent1 = "
			<table id='results-head1' align='center' cellpadding='1' cellspacing='0' style='width:1200px;display:block;text-align:center'>
			<tr id='thead1' style='line-height:45px;background:linear-gradient(#50B4E6, #3296C8, #1478AA);color:#FAFAFA;border:1px solid #FFF'>
			<th style='width:90px;border:1px solid #FFF'>车次</th>
			<th style='width:250px;border:1px solid #FFF'>到发时刻</th>
			<th style='width:60px;border:1px solid #FFF'>历时</th>
			<th style='width:100px;border:1px solid #FFF'>始发站</th>
			<th style='width:100px;border:1px solid #FFF'>终到站</th>
			<th style='width:60px;border:1px solid #FFF'>商务</th>
			<th style='width:60px;border:1px solid #FFF'>特等</th>
			<th style='width:60px;border:1px solid #FFF'>一等</th>
			<th style='width:60px;border:1px solid #FFF'>二等</th>
			<th style='width:60px;border:1px solid #FFF'>高软</th>
			<th style='width:60px;border:1px solid #FFF'>软座</th>
			<th style='width:60px;border:1px solid #FFF'>软卧</th>
			<th style='width:60px;border:1px solid #FFF'>硬卧</th>
			<th style='width:60px;border:1px solid #FFF'>硬座</th>
			<th style='width:60px;border:1px solid #FFF'>无座</th>
			</tr>";
			// 第二个表格的HTML代码表示的表头
			$tableContent2 = "
			<table id='results-head2' align='center' border='0' cellpadding='1' cellspacing='0' style='width:1200px;display:block;text-align:center'>
			<tr id='thead2' style='line-height:45px;background:linear-gradient(#50B4E6, #3296C8, #1478AA);color:#FAFAFA;border:1px solid #FFF'>
			<th style='width:90px;border:1px solid #FFF'>车次</th>
			<th style='width:250px;border:1px solid #FFF'>到发时刻</th>
			<th style='width:60px;border:1px solid #FFF'>历时</th>
			<th style='width:100px;border:1px solid #FFF'>始发站</th>
			<th style='width:100px;border:1px solid #FFF'>终到站</th>
			<th style='width:60px;border:1px solid #FFF'>商务</th>
			<th style='width:60px;border:1px solid #FFF'>特等</th>
			<th style='width:60px;border:1px solid #FFF'>一等</th>
			<th style='width:60px;border:1px solid #FFF'>二等</th>
			<th style='width:60px;border:1px solid #FFF'>高软</th>
			<th style='width:60px;border:1px solid #FFF'>软座</th>
			<th style='width:60px;border:1px solid #FFF'>软卧</th>
			<th style='width:60px;border:1px solid #FFF'>硬卧</th>
			<th style='width:60px;border:1px solid #FFF'>硬座</th>
			<th style='width:60px;border:1px solid #FFF'>无座</th>
			</tr>";
			// 添加第一个表格（出发站—中转站）
			for ($i = 0; $i < count($trainData1["data"]); $i++) {
				if ($trainData1["data"][$i]["queryLeftNewDTO"]["lishi"] == "99:59") continue;
				else {
					$tableContent1 = $tableContent1."
					<tr class='".trainType($trainData1["data"][$i]["queryLeftNewDTO"]["station_train_code"])."' style='line-height:45px;background:#C8D7E6' onmouseover='showTrBgColor(this, 180, 195, 210)' onmouseout='showTrBgColor(this, 200, 215, 230)'>
					<td align='center' style='width:84px;font-family:Arial;font-size:27px;font-weight:bold;border:1px solid #FFF'>
					<a id='".$trainData1["data"][$i]["queryLeftNewDTO"]["station_train_code"]."|".$trainData1["data"][$i]["queryLeftNewDTO"]["train_no"]."|".$fromStationCode."|".$transferStationCode."|".$dateString."|".$trainData1["data"][$i]["queryLeftNewDTO"]["start_station_telecode"]."|".$trainData1["data"][$i]["queryLeftNewDTO"]["end_station_telecode"]."' title='点击查看经停站信息' href='javascript:;' class='train_no_disp' onclick='viewTrainDetail(this)' style='color:#963296' onmouseover='setTextColor(this, 0, 0, 255)' onmouseout='setTextColor(this, 150, 50, 150)' onclick='setTextColor(this, 0, 0, 255)'>".$trainData1["data"][$i]["queryLeftNewDTO"]["station_train_code"]."</a></td>
					<td class='daofaxinxi' style='width:240px;border:1px solid #FFF'>
					<table align='center'><tr valign='middle' style='line-height:30px'>
					<td align='center'>".fromStationDisp($trainData1["data"][$i]["queryLeftNewDTO"]["from_station_name"], $trainData1["data"][$i]["queryLeftNewDTO"]["start_station_name"])."<br><span class='chufashijian' style='font-family:Arial;font-size:22px'>".$trainData1["data"][$i]["queryLeftNewDTO"]["start_time"]."</span></td>
					<td align='center'>—></td>
					<td align='center'>".toStationDisp($trainData1["data"][$i]["queryLeftNewDTO"]["to_station_name"], $trainData1["data"][$i]["queryLeftNewDTO"]["end_station_name"])."<br><span class='daodashijian' style='font-family:Arial;font-size:22px'>".$trainData1["data"][$i]["queryLeftNewDTO"]["arrive_time"]."</span></td>
					</tr></table></td>
					<td class='lishi' style='width:60px;border:1px solid #FFF'>".$trainData1["data"][$i]["queryLeftNewDTO"]["lishi"]."</td>
					<td class='shifazhan' style='width:100px;border:1px solid #FFF'>".$trainData1["data"][$i]["queryLeftNewDTO"]["start_station_name"]."</td>
					<td class='zhongdaozhan' style='width:100px;border:1px solid #FFF'>".$trainData1["data"][$i]["queryLeftNewDTO"]["end_station_name"]."</td>
					<td class='shangwuzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["swz_num"])."</td>
					<td class='tedengzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["tz_num"])."</td>
					<td class='yidengzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["zy_num"])."</td>
					<td class='erdengzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["ze_num"])."</td>
					<td class='gaojiruanwo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["gr_num"])."</td>
					<td class='ruanzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["rz_num"])."</td>
					<td class='ruanwo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["rw_num"])."</td>
					<td class='yingwo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["yw_num"])."</td>
					<td class='yingzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["yz_num"])."</td>
					<td class='wuzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData1["data"][$i]["queryLeftNewDTO"]["wz_num"])."</td></tr>";
					$cnt1++;
				}
			}
			$tableContent1 = $tableContent1."</table>";
			// 添加第二个表格（中转站—目的站）
			for ($j = 0; $j < count($trainData2["data"]); $j++) {
				if ($trainData2["data"][$j]["queryLeftNewDTO"]["lishi"] == "99:59") continue;
				else {
					$tableContent2 = $tableContent2."
					<tr class='".trainType($trainData2["data"][$j]["queryLeftNewDTO"]["station_train_code"])."' style='line-height:45px;background:#C8D7E6;border:1px solid #FFF' onmouseover='showTrBgColor(this, 180, 195, 210)' onmouseout='showTrBgColor(this, 200, 215, 230)'>
					<td align='center' style='width:84px;font-family:Arial;font-size:27px;font-weight:bold;border:1px solid #FFF'>
					<a id='".$trainData2["data"][$j]["queryLeftNewDTO"]["station_train_code"]."|".$trainData2["data"][$j]["queryLeftNewDTO"]["train_no"]."|".$transferStationCode."|".$toStationCode."|".$dateString."|".$trainData2["data"][$j]["queryLeftNewDTO"]["start_station_telecode"]."|".$trainData2["data"][$j]["queryLeftNewDTO"]["end_station_telecode"]."' title='点击查看经停站信息' href='javascript:;' class='train_no_disp' onclick='viewTrainDetail(this)' style='color:#963296' onmouseover='setTextColor(this, 0, 0, 255)' onmouseout='setTextColor(this, 150, 50, 150)' onclick='setTextColor(this, 0, 0, 255)'>".$trainData2["data"][$j]["queryLeftNewDTO"]["station_train_code"]."</a></td>
					<td class='daofaxinxi' style='width:240px;border:1px solid #FFF'>
					<table align='center'><tr valign='middle' style='line-height:30px'>
					<td align='center'>".fromStationDisp($trainData2["data"][$j]["queryLeftNewDTO"]["from_station_name"], $trainData2["data"][$j]["queryLeftNewDTO"]["start_station_name"])."<br><span class='chufashijian' style='font-family:Arial;font-size:22px'>".$trainData2["data"][$j]["queryLeftNewDTO"]["start_time"]."</span></td>
					<td align='center'>—></td>
					<td align='center'>".toStationDisp($trainData2["data"][$j]["queryLeftNewDTO"]["to_station_name"], $trainData2["data"][$j]["queryLeftNewDTO"]["end_station_name"])."<br><span class='daodashijian' style='font-family:Arial;font-size:22px'>".$trainData2["data"][$j]["queryLeftNewDTO"]["arrive_time"]."</span></td>
					</tr></table></td>
					<td class='lishi' style='width:60px;border:1px solid #FFF'>".$trainData2["data"][$j]["queryLeftNewDTO"]["lishi"]."</td>
					<td class='shifazhan' style='width:100px;border:1px solid #FFF'>".$trainData2["data"][$j]["queryLeftNewDTO"]["start_station_name"]."</td>
					<td class='zhongdaozhan' style='width:100px;border:1px solid #FFF'>".$trainData2["data"][$j]["queryLeftNewDTO"]["end_station_name"]."</td>
					<td class='shangwuzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["swz_num"])."</td>
					<td class='tedengzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["tz_num"])."</td>
					<td class='yidengzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["zy_num"])."</td>
					<td class='erdengzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["ze_num"])."</td>
					<td class='gaojiruanwo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["gr_num"])."</td>
					<td class='ruanzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["rz_num"])."</td>
					<td class='ruanwo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["rw_num"])."</td>
					<td class='yingwo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["yw_num"])."</td>
					<td class='yingzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["yz_num"])."</td>
					<td class='wuzuo' style='width:60px;border:1px solid #FFF'>".dispSeatsNum($trainData2["data"][$j]["queryLeftNewDTO"]["wz_num"])."</td></tr>";
					$cnt2++;
				}
			}
			$tableContent2 = $tableContent2."</table>";
			$normalResp = "
			<h2 align='center' style='color:#33F'>1.&nbsp;<span style='color:#1E9650'>".$fromStationName."</span>&nbsp;—>&nbsp;<span style='color:#1E9650'>".$transferStationName."</span>&nbsp;（<span style='color:#1E9650'>".$cnt1."</span>&nbsp;个车次）</h2>".$tableContent1."
			<h2 align='center' style='color:#33F'>2.&nbsp;<span style='color:#1E9650'>".$transferStationName."</span>&nbsp;—>&nbsp;<span style='color:#1E9650'>".$toStationName."</span>&nbsp;（<span style='color:#1E9650'>".$cnt2."</span>&nbsp;个车次）</h2>".$tableContent2;
			$normalResp = $normalResp."</table>";
			echo($normalResp);
		}
		else if (count($trainData1["data"]) == 0 && count($trainData2["data"]) > 0) {
			$errorResp3 = "<h2 align='center' style='color:#66F'>从&nbsp;<span style='color:#1E9650'>".$fromStationName."</span>&nbsp;到&nbsp;<span style='color:#1E9650'>".$transferStationName."</span>&nbsp;未查询到车次信息，请更改查询日期或更换中转站后再试。</h2>";
			echo($errorResp3);
		}
		else if (count($trainData1["data"]) > 0 && count($trainData2["data"]) == 0) {
			$errorResp3 = "<h2 align='center' style='color:#66F'>从&nbsp;<span style='color:#1E9650'>".$transferStationName."</span>&nbsp;到&nbsp;<span style='color:#1E9650'>".$toStationName."</span>&nbsp;未查询到车次信息，请更改查询日期或更换中转站后再试。</h2>";
			echo($errorResp3);
		}
		else echo($errorResp2);
	}
	else echo($errorResp1);
	fclose($f6); fclose($f7);
}
else echo($errorResp1);
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
	else if ($seatsNum == "--" || $seatsNum == "无") $displayStr = $seatsNum;
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
?>