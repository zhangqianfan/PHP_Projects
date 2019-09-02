var times = new Array("08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00");
var allStations = new Array(21);
function isZmnameLegal() { // 判断所填站名是否合法
	var stationName1 = document.getElementById("sta-name").value;
	if (stationName1) {
		for (var p = 0; p < station_names_str.length; p++) {
			if (station_names_arr[p][1] == stationName1) break;
		}
		return p < station_names_str.length;
	}
	return false;
}
function executeSearch() {
	for (var i = 0; i < 21; i++) {
		var staStr = document.getElementById("stations" + i).innerHTML;
		allStations[i] = staStr.split("、");
	}
	if (isZmnameLegal()) {
		var stationName2 = document.getElementById("sta-name").value;
		for (var j = 0; j < 21; j++) {
			for (var k = 0; k < allStations[j].length; k++) {
				if (allStations[j][k] == stationName2) break;
			}
			if (k < allStations[j].length) break;
		}
		if (j < 21) {
			document.getElementById("span1-station").innerHTML = stationName2;
			document.getElementById("span1-time").innerHTML = times[j];
			document.getElementById("tipbox").style.display = "block";
		}
		else alert("该车站不存在！");
	}
	else alert("车站输入错误！");
	document.getElementById("sta-name").value = "";
}
function moveQueryToTop() {
	var queryModule = document.getElementById("query-div");
	if ($(document).scrollTop() >= 64) {
		queryModule.setAttribute("style", "top:0px;left:34.7%;position:fixed;background:rgba(255,255,255,0.8)");
	}
	else {
		queryModule.setAttribute("style", "height:50px");
	}
}
function closeMsgBox() {
	document.getElementById("tipbox").style.display = "none";
}