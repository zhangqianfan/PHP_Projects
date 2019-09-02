var xhr;
function showResponse(fromStation, transferStation, toStation, dateString) {
	for (var fromIndex = 0; fromIndex < station_names_str.length; fromIndex++) {
		if (fromStation == station_names_arr[fromIndex][1]) break;
	}
	for (var transferIndex = 0; transferIndex < station_names_str.length; transferIndex++) {
		if (transferStation == station_names_arr[transferIndex][1]) break;
	}
	for (var toIndex = 0; toIndex < station_names_str.length; toIndex++) {
		if (toStation == station_names_arr[toIndex][1]) break;
	}
	var fromStationCode = station_names_arr[fromIndex][2];
	var transferStationCode = station_names_arr[transferIndex][2];
	var toStationCode = station_names_arr[toIndex][2];
	if (fromStationCode != "" && transferStationCode != "" && toStationCode != "" && dateString != "") {
		xhr = createXmlHttpRequest();
		if (xhr == null) {
			alert("抱歉，您的浏览器不支持XMLHttpRequest对象！");
		}
		else {
			var url = "http://127.0.0.1/external/train/online/response-transit-info.php?from=" + fromStation + "&from_station_code=" + fromStationCode + "&transfer=" + transferStation + "&transfer_station_code=" + transferStationCode + "&to=" + toStation + "&to_station_code=" + toStationCode + "&date=" + dateString;
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
			document.getElementById("resp").innerHTML = xhr.responseText;
		}
		else alert("响应为空！");
	}
}