<?php
header("Content-Type:text/html;charset=utf-8");
include 'station-dict.php';
$fromStation = $_GET["from"];
$toStation = $_GET["to"];
$dateString = $_GET["date"];
$ticketType = $_GET["ticket_type"];
$errorResp = "<h2 align='center' style='color:#FA0006'>查询失败，请检查网络或服务器后重试或切换到离线版查询！</h2>";
$normalResp = "<table id='results-content' border='1' cellpadding='1' cellspacing='0' width='1200px' align='center' style='background:#C8D7E6;border:1px solid #FFF'>";
if ($fromStation && $toStation && $dateString && $ticketType) {
	$reqUrl = "https://kyfw.12306.cn/otn/leftTicket/query?leftTicketDTO.train_date=".$dateString."&leftTicketDTO.from_station=".$fromStation."&leftTicketDTO.to_station=".$toStation."&purpose_codes=ADULT";
	$f4 = fopen($reqUrl, "r");
	if ($f4) {
		$jsonStr = stream_get_contents($f4);
		$trainDataArr = json_decode($jsonStr, true);
		/* 
		if (count($trainData["data"]) > 0) {
			// A1-硬座；A2-软座；A3-硬卧；A4-软卧；A6-高级软卧；
			// A9-商务座；O-二等座；M-一等座；P-特等座；WZ-无座；
			$keySet = Array("A1", "A2", "A3", "A4", "A6", "A9", "O", "M", "P", "WZ"); // 价格数组键的集合
			$seatSet = Array("yingzuo", "ruanzuo", "yingwo", "ruanwo", "gaojiruanwo", "shangwuzuo", "erdengzuo", "yidengzuo", "tedengzuo", "wuzuo"); // 席位类型
			$priceSet = Array(); // 价格数组
			for ($p = 0; $p < 10; $p++) $priceSet[$seatSet[$p]] = "--";
			for ($i = 0; $i < count($trainData["data"]); $i++) {
				if ($trainData["data"][$i]["queryLeftNewDTO"]["lishi"] == "99:59") continue;
				else {
					// 输出车次、到发信息、历时、始发站、终点站
					$normalResp = $normalResp."
					<tr id='".$trainData["data"][$i]["queryLeftNewDTO"]["train_no"]."' class='".trainType($trainData["data"][$i]["queryLeftNewDTO"]["station_train_code"])."' style='line-height:45px' onmouseover='showTrBgColor(this, 180, 195, 210)' onmouseout='showTrBgColor(this, 200, 215, 230)'>
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
					<td class='zhongdaozhan' style='width:100px'>".$trainData["data"][$i]["queryLeftNewDTO"]["end_station_name"]."</td>";
					// 票价查询URL
					$priceQueryUrl = "https://kyfw.12306.cn/otn/leftTicket/queryTicketPrice?train_no=".$trainData["data"][$i]["queryLeftNewDTO"]["train_no"]."&from_station_no=".$trainData["data"][$i]["queryLeftNewDTO"]["from_station_no"]."&to_station_no=".$trainData["data"][$i]["queryLeftNewDTO"]["to_station_no"]."&seat_types=".$trainData["data"][$i]["queryLeftNewDTO"]["seat_types"]."&train_date=".$dateString;
					$f5 = fopen($priceQueryUrl, "r");
					if ($f5) {
						$jsonCont = stream_get_contents($f5);
						if ($jsonCont != "-1") {
							// $priceCont为解析JSON之后得到的数组，而$priceSet为自行定义的数组，注意区别
							$priceCont = json_decode($jsonCont, true);
							if (count($priceCont["data"]) > 0) {
								switch ($ticketType) {
									case "chengren": // 成人票显示原有数据即可
										for ($j1 = 0; $j1 < 10; $j1++) {
											if ($priceCont["data"][$keySet[$j1]]) {
												$priceSet[$seatSet[$j1]] = $priceCont["data"][$keySet[$j1]];
											}
											else {
												$priceSet[$seatSet[$j1]] = "--";
											}
										}
										break;
									case "xuesheng": // 学生票只对硬座、无座、硬卧、高铁二等座有优惠
										for ($j2 = 0; $j2 < 10; $j2++) {
											if ($priceCont["data"][$keySet[$j2]]) {
												// $priceRealNumber：以实数表示的成人票价
												$priceString = $priceCont["data"][$keySet[$j2]];
												$priceRealNumber = floatval(substr($priceString, 2, strlen($priceString) - 1));
												if ($j2 == 0 || $j2 == 9) { // 硬座、无座票价均为对应席别成人票价的一半
													$priceRealNumber = priceRound($priceRealNumber / 2);
												}
												else if ($j2 == 2) { // 硬卧票价为成人票价减去硬座票价的一半
													$priceRealNumber = priceRound($priceRealNumber - $priceCont["data"][1] / 20);
												}
												else if ($j2 == 6) { // 高铁票价为成人票价的75%
													$priceRealNumber = priceRound($priceRealNumber * 0.75);
												}
												else continue;
												$priceSet[$seatSet[$j2]] = "¥".number_format($priceRealNumber, 1);
												//$priceSet[$seatSet[$j2]] = "¥".$priceRealNumber;
											}
											else {
												$priceSet[$seatSet[$j2]] = "--";
											}
										}
										break;
									case "ertong": // 儿童票价跟学生票价计算方法相同
										for ($j3 = 0; $j3 < 10; $j3++) {
											if ($priceCont["data"][$keySet[$j3]]) {
												$priceString = $priceCont["data"][$keySet[$j3]];
												$priceRealNumber = floatval(substr($priceString, 2, strlen($priceString) - 1));
												if ($j3 == 0 || $j3 == 9) {
													$priceRealNumber = priceRound($priceRealNumber / 2);
												}
												else if ($j3 == 2) {
													$priceRealNumber = priceRound($priceRealNumber - $priceCont["data"][1] / 20);
												}
												else if ($j3 == 6) {
													$priceRealNumber = priceRound($priceRealNumber * 0.75);
												}
												else continue;
												$priceSet[$seatSet[$j3]] = "¥".number_format($priceRealNumber, 1);
											}
											else {
												$priceSet[$seatSet[$j3]] = "--";
											}
										}
										break;
									case "canjun": // 残军票价所有席别均为对应成人票价的一半
										for ($j4 = 0; $j4 < 10; $j4++) {
											if ($priceCont["data"][$keySet[$j4]]) {
												$priceString = $priceCont["data"][$keySet[$j4]];
												$priceRealNumber = floatval(substr($priceString, 2, strlen($priceString) - 1));
												$priceRealNumber = priceRound($priceRealNumber / 2);
												$priceSet[$seatSet[$j4]] = "¥".number_format($priceRealNumber, 1);
											}
											else {
												$priceSet[$seatSet[$j4]] = "--";
											}
										}
										break;
									default:
										for ($j5 = 0; $j5 < 10; $j5++) $priceSet[$seatSet[$j5]] = "empty";
										break;
								}
							}
							else {
								for ($j = 0; $j < 10; $j++) $priceSet[$seatSet[$j]] = "null";
							}
						}
						// PE：Parameter Error（参数错误）；NE：Network Error（网络错误）
						else {
							for ($j = 0; $j < 10; $j++) $priceSet[$seatSet[$j]] = "PE";
						}
					}
					else {
						for ($j = 0; $j < 10; $j++) $priceSet[$seatSet[$j]] = "NE";
					}
					// 输出票价信息
					$normalResp = $normalResp."
					<td id='shangwuzuo_".$i."' class='shangwuzuo' style='width:60px'>".$priceSet["shangwuzuo"]."</td>
					<td id='tedengzuo_".$i."' class='tedengzuo' style='width:60px'>".$priceSet["tedengzuo"]."</td>
					<td id='yidengzuo_".$i."' class='yidengzuo' style='width:60px'>".$priceSet["yidengzuo"]."</td>
					<td id='erdengzuo_".$i."' class='erdengzuo' style='width:60px'>".$priceSet["erdengzuo"]."</td>
					<td id='gaojiruanwo_".$i."' class='gaojiruanwo' style='width:60px'>".$priceSet["gaojiruanwo"]."</td>
					<td id='ruanzuo_".$i."' class='ruanzuo' style='width:60px'>".$priceSet["ruanzuo"]."</td>
					<td id='ruanwo_".$i."' class='ruanwo' style='width:60px'>".$priceSet["ruanwo"]."</td>
					<td id='yingwo_".$i."' class='yingwo' style='width:60px'>".$priceSet["yingwo"]."</td>
					<td id='yingzuo_".$i."' class='yingzuo' style='width:60px'>".$priceSet["yingzuo"]."</td>
					<td id='wuzuo_".$i."' class='wuzuo' style='width:60px'>".$priceSet["wuzuo"]."</td></tr>";
					fclose($f5);
				}
			}
			$normalResp = $normalResp."</table>";
			echo($normalResp);
		}
		*/
		$trainData = Array();
		if (count($trainDataArr["data"]["result"]) > 0) {
			// A1-硬座；A2-软座；A3-硬卧；A4-软卧；A6-高级软卧；
			// A9-商务座；O-二等座；M-一等座；P-特等座；WZ-无座；
			$keySet = Array("A1", "A2", "A3", "A4", "A6", "A9", "O", "M", "P", "WZ"); // 价格数组键的集合
			$seatSet = Array("yingzuo", "ruanzuo", "yingwo", "ruanwo", "gaojiruanwo", "shangwuzuo", "erdengzuo", "yidengzuo", "tedengzuo", "wuzuo"); // 席位类型
			$priceSet = Array(); // 价格数组
			for ($p = 0; $p < 10; $p++) $priceSet[$seatSet[$p]] = "--";
			for ($ind = 0; $ind < count($trainDataArr["data"]["result"]); $ind++) {
				$trainData[$ind] = explode("|", $trainDataArr["data"]["result"][$ind]);
			}
			for ($i = 0; $i < count($trainData); $i++) {
				if ($trainData[$i][10] == "99:59") continue;
				else {
					// 输出车次、到发信息、历时、始发站、终点站
					$normalResp = $normalResp."
					<tr class='".trainType($trainData[$i][3])."' style='line-height:45px' onmouseover='showTrBgColor(this, 180, 195, 210)' onmouseout='showTrBgColor(this, 200, 215, 230)'>
					<td align='center' style='width:84px;font-family:Arial;font-size:27px;font-weight:bold'>
					<a id='".$trainData[$i][3]."|".$trainData[$i][2]."|".$fromStation."|".$toStation."|".dateStringFormat($trainData[$i][13])."|".$trainData[$i][4]."|".$trainData[$i][5]."' title='点击查看经停站信息' href='javascript:;' class='train_no_disp' onclick='viewTrainDetail(this)' style='color:#963296' onmouseover='setTextColor(this, 0, 0, 255)' onmouseout='setTextColor(this, 150, 50, 150)' onclick='setTextColor(this, 0, 0, 255)'>".$trainData[$i][3]."</a></td>
					<td class='daofaxinxi' style='width:240px'>
					<table align='center'><tr valign='middle' style='line-height:30px'>
					<td align='center'>".fromStationDisp(stationCodeToName($trainData[$i][6]), stationCodeToName($trainData[$i][4]))."<br><span class='chufashijian' style='font-family:Arial;font-size:22px'>".$trainData[$i][8]."</span></td>
					<td align='center'>—></td>
					<td align='center'>".toStationDisp(stationCodeToName($trainData[$i][7]), stationCodeToName($trainData[$i][5]))."<br><span class='daodashijian' style='font-family:Arial;font-size:22px'>".$trainData[$i][9]."</span></td>
					</tr></table></td>
					<td class='lishi' style='width:60px'>".$trainData[$i][10]."</td>
					<td class='shifazhan' style='width:100px'>".stationCodeToName($trainData[$i][4])."</td>
					<td class='zhongdaozhan' style='width:100px'>".stationCodeToName($trainData[$i][5])."</td>";
					// 票价查询URL
					$priceQueryUrl = "https://kyfw.12306.cn/otn/leftTicket/queryTicketPrice?train_no=".$trainData[$i][2]."&from_station_no=".$trainData[$i][16]."&to_station_no=".$trainData[$i][17]."&seat_types=".$trainData[$i][34]."&train_date=".$dateString;
					$f5 = fopen($priceQueryUrl, "r");
					if ($f5) {
						$jsonCont = stream_get_contents($f5);
						if ($jsonCont != "-1") {
							// $priceCont为解析JSON之后得到的数组，而$priceSet为自行定义的数组，注意区别
							$priceCont = json_decode($jsonCont, true);
							if (count($priceCont["data"]) > 0) {
								switch ($ticketType) {
									case "chengren": // 成人票显示原有数据即可
										for ($j1 = 0; $j1 < 10; $j1++) {
											if ($priceCont["data"][$keySet[$j1]]) {
												$priceSet[$seatSet[$j1]] = $priceCont["data"][$keySet[$j1]];
											}
											else {
												$priceSet[$seatSet[$j1]] = "--";
											}
										}
										break;
									case "xuesheng": // 学生票只对硬座、无座、硬卧、高铁二等座有优惠
										for ($j2 = 0; $j2 < 10; $j2++) {
											if ($priceCont["data"][$keySet[$j2]]) {
												// $priceRealNumber：以实数表示的成人票价
												$priceString = $priceCont["data"][$keySet[$j2]];
												$priceRealNumber = floatval(substr($priceString, 2, strlen($priceString) - 1));
												if ($j2 == 0 || $j2 == 9) { // 硬座、无座票价均为对应席别成人票价的一半
													$priceRealNumber = priceRound($priceRealNumber / 2);
												}
												else if ($j2 == 2) { // 硬卧票价为成人票价减去硬座票价的一半
													$priceRealNumber = priceRound($priceRealNumber - $priceCont["data"][1] / 20);
												}
												else if ($j2 == 6) { // 高铁票价为成人票价的75%
													$priceRealNumber = priceRound($priceRealNumber * 0.75);
												}
												else continue;
												$priceSet[$seatSet[$j2]] = "¥".number_format($priceRealNumber, 1);
												//$priceSet[$seatSet[$j2]] = "¥".$priceRealNumber;
											}
											else {
												$priceSet[$seatSet[$j2]] = "--";
											}
										}
										break;
									case "ertong": // 儿童票价跟学生票价计算方法相同
										for ($j3 = 0; $j3 < 10; $j3++) {
											if ($priceCont["data"][$keySet[$j3]]) {
												$priceString = $priceCont["data"][$keySet[$j3]];
												$priceRealNumber = floatval(substr($priceString, 2, strlen($priceString) - 1));
												if ($j3 == 0 || $j3 == 9) {
													$priceRealNumber = priceRound($priceRealNumber / 2);
												}
												else if ($j3 == 2) {
													$priceRealNumber = priceRound($priceRealNumber - $priceCont["data"][1] / 20);
												}
												else if ($j3 == 6) {
													$priceRealNumber = priceRound($priceRealNumber * 0.75);
												}
												else continue;
												$priceSet[$seatSet[$j3]] = "¥".number_format($priceRealNumber, 1);
											}
											else {
												$priceSet[$seatSet[$j3]] = "--";
											}
										}
										break;
									case "canjun": // 残军票价所有席别均为对应成人票价的一半
										for ($j4 = 0; $j4 < 10; $j4++) {
											if ($priceCont["data"][$keySet[$j4]]) {
												$priceString = $priceCont["data"][$keySet[$j4]];
												$priceRealNumber = floatval(substr($priceString, 2, strlen($priceString) - 1));
												$priceRealNumber = priceRound($priceRealNumber / 2);
												$priceSet[$seatSet[$j4]] = "¥".number_format($priceRealNumber, 1);
											}
											else {
												$priceSet[$seatSet[$j4]] = "--";
											}
										}
										break;
									default:
										for ($j5 = 0; $j5 < 10; $j5++) $priceSet[$seatSet[$j5]] = "empty";
										break;
								}
							}
							else {
								for ($j = 0; $j < 10; $j++) $priceSet[$seatSet[$j]] = "null";
							}
						}
						// PE：Parameter Error（参数错误）；NE：Network Error（网络错误）
						else {
							for ($j = 0; $j < 10; $j++) $priceSet[$seatSet[$j]] = "PE";
						}
					}
					else {
						for ($j = 0; $j < 10; $j++) $priceSet[$seatSet[$j]] = "NE";
					}
					// 输出票价信息
					$normalResp = $normalResp."
					<td id='shangwuzuo_".$i."' class='shangwuzuo' style='width:60px'>".$priceSet["shangwuzuo"]."</td>
					<td id='tedengzuo_".$i."' class='tedengzuo' style='width:60px'>".$priceSet["tedengzuo"]."</td>
					<td id='yidengzuo_".$i."' class='yidengzuo' style='width:60px'>".$priceSet["yidengzuo"]."</td>
					<td id='erdengzuo_".$i."' class='erdengzuo' style='width:60px'>".$priceSet["erdengzuo"]."</td>
					<td id='gaojiruanwo_".$i."' class='gaojiruanwo' style='width:60px'>".$priceSet["gaojiruanwo"]."</td>
					<td id='ruanzuo_".$i."' class='ruanzuo' style='width:60px'>".$priceSet["ruanzuo"]."</td>
					<td id='ruanwo_".$i."' class='ruanwo' style='width:60px'>".$priceSet["ruanwo"]."</td>
					<td id='yingwo_".$i."' class='yingwo' style='width:60px'>".$priceSet["yingwo"]."</td>
					<td id='yingzuo_".$i."' class='yingzuo' style='width:60px'>".$priceSet["yingzuo"]."</td>
					<td id='wuzuo_".$i."' class='wuzuo' style='width:60px'>".$priceSet["wuzuo"]."</td></tr>";
					fclose($f5);
				}
			}
			$normalResp = $normalResp."</table>";
			echo($normalResp);
		}
		else echo("<h2 align='center' style='color:#66F'>未查询到车次信息，请更改查询日期或尝试中转查询。</h2>");
	}
	else echo($errorResp);
	fclose($f4);
}
else echo("<h2 align='center' style='color:#FA0006'>参数错误！</h2>");
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
function priceRound($priceArg) { // 对票价进行舍入处理的函数（不是简单的四舍五入）
	if (is_nan($priceArg)) return 0;
	else {
		$integerPart = floor($priceArg); // 整数部分
		$decimalPart = $priceArg - $integerPart; // 小数部分
		if ($decimalPart < 0.25) {
			return $priceArg;
		}
		else if ($decimalPart >= 0.25 && $decimalPart < 0.75) {
			return $integerPart + 0.5;
		}
		else return $integerPart + 1;
	}
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