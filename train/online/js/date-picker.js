function openDatePicker() { // 打开日期选择器
	var date1 = document.getElementById("riqi").innerHTML;
	var date_array = date1.split("-");
	document.getElementById("yyyy").innerHTML = date_array[0];
	document.getElementById("mm").innerHTML = (date_array[1].charAt(0) == '0') ? date_array[1].substr(1) : date_array[1];
	document.getElementById("date-picker").style.display = "block";
	var year1 = parseInt(date_array[0]);
	var month1 = parseInt(date_array[1]);
	var day1 = parseInt(date_array[2]);
	showMonthCalendar(year1, month1);
	
}
function isLeapYear(yearArg) {
	return typeof(yearArg) == "number" && ((yearArg % 4 == 0 && yearArg % 100 != 0) || yearArg % 400 == 0);
}
function closeDatePicker() {
	document.getElementById("date-picker").style.display = "none";
}
function chooseToday() {
	initDate();
	closeDatePicker();
}
function showMonthCalendar(yearArg, monthArg) { // 显示yearArg年monthArg月的月历
	for (var i = 1; i < 43; i++) {
		document.getElementById("d" + i).innerHTML = "&nbsp;";
	}
	if (typeof(yearArg) == "number" && typeof(monthArg) == "number") {
		var date0 = new Date(yearArg, monthArg - 1, 1);
		var week0 = date0.getDay();
		var days_of_months = new Array(31, (isLeapYear(yearArg)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		var today = new Date();
		var pre_sale = new Date(); pre_sale.setDate(pre_sale.getDate() + 29);
		var selected_day = parseInt(document.getElementById("riqi").innerHTML.substr(8));
		if (yearArg == today.getFullYear() && monthArg == today.getMonth() + 1) {
			for (var j = 1; j < today.getDate(); j++) {
				document.getElementById("d" + (j + week0)).innerHTML = j;
				document.getElementById("d" + (j + week0)).style.color = "#EEE";
				document.getElementById("d" + (j + week0)).style.fontFamily = "Arial";
				document.getElementById("d" + (j + week0)).style.fontSize = "18px";
			}
			for (var k = today.getDate(); k <= days_of_months[monthArg - 1]; k++) {
				if (k == today.getDate()) {
					document.getElementById("d" + (k + week0)).innerHTML = "<a href='javascript:;' id='today-day' onclick='chooseDay(" + k + ")'>" + k + "</a>";
				}
				if (k == selected_day) {
					document.getElementById("d" + (k + week0)).innerHTML = "<a href='javascript:;' id='selected-day' onclick='chooseDay(" + k + ")'>" + k + "</a>";
				}
				else {
					document.getElementById("d" + (k + week0)).innerHTML = "<a href='javascript:;' class='date-anchor' onclick='chooseDay(" + k + ")'>" + k + "</a>";
				}
			}
		}
		else if (yearArg == pre_sale.getFullYear() && monthArg == pre_sale.getMonth() + 1) {
			for (var u = 1; u <= pre_sale.getDate(); u++) {
				if (u == selected_day) {
					document.getElementById("d" + (u + week0)).innerHTML = "<a href='javascript:;' id='selected-day' onclick='chooseDay(" + u + ")'>" + u + "</a>";
				}
				else {
					document.getElementById("d" + (u + week0)).innerHTML = "<a href='javascript:;' class='date-anchor' onclick='chooseDay(" + u + ")'>" + u + "</a>";
				}
			}
			for (var v = pre_sale.getDate() + 1; v <= days_of_months[monthArg - 1]; v++) {
				document.getElementById("d" + (v + week0)).innerHTML = v;
				document.getElementById("d" + (v + week0)).style.color = "#EEE";
				document.getElementById("d" + (v + week0)).style.fontFamily = "Arial";
				document.getElementById("d" + (v + week0)).style.fontSize = "18px";
			}
		}
		else {
			for (var w = 1; w <= days_of_months[monthArg - 1]; w++) {
				document.getElementById("d" + (w + week0)).innerHTML = w;
				document.getElementById("d" + (w + week0)).style.color = "#EEE";
				document.getElementById("d" + (w + week0)).style.fontFamily = "Arial";
				document.getElementById("d" + (w + week0)).style.fontSize = "18px";
			}
		}
	}
}
function lastMonth() {
	var year2 = parseInt(document.getElementById("yyyy").innerHTML);
	var month2 = parseInt(document.getElementById("mm").innerHTML);
	var date2 = new Date();
	if (year2 == date2.getFullYear() && month2 == date2.getMonth() + 1) return;
	else {
		if (month2 == 1) {
			year2--;
			month2 = 12;
		}
		else month2--;
		showMonthCalendar(year2, month2);
		document.getElementById("yyyy").innerHTML = year2;
		document.getElementById("mm").innerHTML = month2;
	}
}
function nextMonth() {
	var year3 = parseInt(document.getElementById("yyyy").innerHTML);
	var month3 = parseInt(document.getElementById("mm").innerHTML);
	if (month3 == 12) {
		year3++;
		month3 = 1;
	}
	else month3++;
	showMonthCalendar(year3, month3);
	document.getElementById("yyyy").innerHTML = year3;
	document.getElementById("mm").innerHTML = month3;
}
function chooseDay(dayArg) {
	if (typeof(dayArg) == "number") {
		var year4 = parseInt(document.getElementById("yyyy").innerHTML);
		var month4 = (parseInt(document.getElementById("mm").innerHTML) < 10) ? ("0" + document.getElementById("mm").innerHTML) : document.getElementById("mm").innerHTML;
		var day4 = (dayArg < 10) ? ("0" + dayArg) : dayArg;
		var date_string = year4 + "-" + month4 + "-" + day4;
		document.getElementById("riqi").innerHTML = date_string;
		closeDatePicker();
	}
}