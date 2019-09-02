var xhr;
function showResponse(fromStation, toStation, dateString) {
	for (var fromIndex = 0; fromIndex < station_names_str.length; fromIndex++) {
		if (fromStation == station_names_arr[fromIndex][1]) break;
	}
	for (var toIndex = 0; toIndex < station_names_str.length; toIndex++) {
		if (toStation == station_names_arr[toIndex][1]) break;
	}
	var fromStationCode = station_names_arr[fromIndex][2];
	var toStationCode = station_names_arr[toIndex][2];
	if (fromStationCode != "" && toStationCode != "" && dateString != "") {
		xhr = createXmlHttpRequest();
		if (xhr == null) {
			alert("抱歉，您的浏览器不支持XMLHttpRequest对象！");
		}
		else {
			var url = "http://127.0.0.1/external/train/online/response-train-info.php?from=" + fromStationCode + "&to=" + toStationCode + "&date=" + dateString;
			xhr.onreadystatechange = stateChange;
			xhr.open("GET", url, true);
			xhr.send(null);
		}
	}
	else document.getElementById("resp").innerHTML = "<h2 align='center' style='color:#F00'>参数错误！</h2>";
}
function createXmlHttpRequest() {
	var XMLHttp;
	try {
		XMLHttp = new XMLHttpRequest();
	}
	catch (e1) {
		try {
			XMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e2) {
			XMLHttp = null;
		}
	}
	return XMLHttp;
}
function stateChange() {
	if (xhr.status == 200 && xhr.readyState == 4) {
		if (xhr.responseText != "") {
			var cfz = document.getElementById("departure").value;
			var mdz = document.getElementById("arrival").value;
			var rq = document.getElementById("riqi").innerHTML;
			var date5 = new Date(parseInt(rq.substr(0, 4)), parseInt(rq.substr(5, 7)) - 1, parseInt(rq.substr(8, 10)));
			var weekdays = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
			if (xhr.responseText.match(/<tr/) != null) {
				var num_of_res = xhr.responseText.match(/<tr class/g).length;
				document.getElementById("num-of-res").innerHTML = date5.getFullYear() + "年" + (date5.getMonth() + 1) + "月" + date5.getDate() + "日（" + weekdays[date5.getDay()] + "），从&nbsp;<span style='color:#00F;font-weight:bold'>" + cfz + "</span>&nbsp;到&nbsp;<span style='color:#00F;font-weight:bold'>" + mdz + "</span>&nbsp;共查询到&nbsp;<span style='color:#00F;font-weight:bold'>" + num_of_res + "</span>&nbsp;个车次";
			}
			else {
				document.getElementById("num-of-res").innerHTML = "&nbsp";
			}
			document.getElementById("resp").innerHTML = xhr.responseText;
		}
		else alert("响应为空！");
	}
}