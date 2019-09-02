var xhr;
function showDelayInfo(id, trainCode) {
	if (id > 0) {
		xhr = createXmlHttpRequest();
		if (xhr == null) {
			alert("抱歉，您的浏览器不支持XMLHttpRequest对象！");
		}
		else {
			var stationName = document.getElementById("station_name_" + id).innerHTML;
			var stationCode = encodeURI(stationName);
			stationCode = stationCode.replace(/%/g, "-");
			var punctualArrivalTime = document.getElementById("arrive_time_" + id).innerHTML;
			var url = "delay-or-normal-info.php?station_name=" + stationName + "&train_code=" + trainCode + "&station_encode=" + stationCode + "&punctual_arrival_time=" + punctualArrivalTime;
			xhr.onreadystatechange = function() {
				if (xhr.status == 200 && xhr.readyState == 4) {
					if (xhr.responseText != "") {
						document.getElementById("delay_or_normal_" + id).innerHTML = xhr.responseText;
					}
					else {
						document.getElementById("delay_or_normal_" + id).innerHTML = "查询失败(点击重试)";
					}
				}
			};
			xhr.open("GET", url, true);
			xhr.send(null);
		}
	}
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