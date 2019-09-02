// 创建列车类和车站类
function Train(train_no, stations) {
	this.train_no = train_no; // 车次
	this.stations = stations; // 经停站集合
}
function getTrainByTrainNo(train_no_arg) {
	var regexp = /^[ZTKGDCP][0-9]{1,4}$/g;
	if (regexp.test(train_no_arg)) {
		var train_type = train_no_arg.substr(0, 1);
		var train_num = train_no_arg.substr(1);
		var sta_num = 0;//经停站数目
		var stas = new Array();
		switch (train_type) {
			case "K":
				sta_num = k[train_num].length / 5;break;
			case "T":
				sta_num = t[train_num].length / 5;break;
			case "Z":
				sta_num = z[train_num].length / 5;break;
			case "G":
				sta_num = g[train_num].length / 5;break;
			case "D":
				sta_num = d[train_num].length / 5;break;
			case "C":
				sta_num = c[train_num].length / 5;break;
			case "P":
				sta_num = p[train_num].length / 5;break;
			default:
				sta_num = 0;break;
		}
		var day_arg = 1;
		for (var i = 0; i < sta_num; i++) {
			switch (train_type) {
				case "K":
					if (i == 0) {
						stas[i] = new Station(train_no_arg, k[train_num][1], 1, k[train_num][2], k[train_num][3], k[train_num][4]);
					}
					else {
						if (k[train_num][5 * i - 3] != "发站") {
							if (k[train_num][5 * i - 3] > k[train_num][5 * i + 2]) day_arg++;
						}
						else {
							if (k[train_num][5 * i - 2] > k[train_num][5 * i + 2]) day_arg++;
						}
						stas[i] = new Station(train_no_arg, k[train_num][5 * i + 1], day_arg, k[train_num][5 * i + 2], k[train_num][5 * i + 3], k[train_num][5 * i + 4]);
					}
					break;
				case "T":
					if (i == 0) {
						stas[i] = new Station(train_no_arg, t[train_num][1], 1, t[train_num][2], t[train_num][3], t[train_num][4]);
					}
					else {
						if (t[train_num][5 * i - 3] != "发站") {
							if (t[train_num][5 * i - 3] > t[train_num][5 * i + 2]) day_arg++;
						}
						else {
							if (t[train_num][5 * i - 2] > t[train_num][5 * i + 2]) day_arg++;
						}
						stas[i] = new Station(train_no_arg, t[train_num][5 * i + 1], day_arg, t[train_num][5 * i + 2], t[train_num][5 * i + 3], t[train_num][5 * i + 4]);
					}
					break;
				case "Z":
					if (i == 0) {
						stas[i] = new Station(train_no_arg, z[train_num][1], 1, z[train_num][2], z[train_num][3], z[train_num][4]);
					}
					else {
						if (z[train_num][5 * i - 3] != "发站") {
							if (z[train_num][5 * i - 3] > z[train_num][5 * i + 2]) day_arg++;
						}
						else {
							if (z[train_num][5 * i - 2] > z[train_num][5 * i + 2]) day_arg++;
						}
						stas[i] = new Station(train_no_arg, z[train_num][5 * i + 1], day_arg, z[train_num][5 * i + 2], z[train_num][5 * i + 3], z[train_num][5 * i + 4]);
					}
					break;
				case "G":
					if (i == 0) {
						stas[i] = new Station(train_no_arg, g[train_num][1], 1, g[train_num][2], g[train_num][3], g[train_num][4]);
					}
					else {
						if (g[train_num][5 * i - 3] != "发站") {
							if (g[train_num][5 * i - 3] > g[train_num][5 * i + 2]) day_arg++;
						}
						else {
							if (g[train_num][5 * i - 2] > g[train_num][5 * i + 2]) day_arg++;
						}
						stas[i] = new Station(train_no_arg, g[train_num][5 * i + 1], day_arg, g[train_num][5 * i + 2], g[train_num][5 * i + 3], g[train_num][5 * i + 4]);
					}
					break;
				case "D":
					if (i == 0) {
						stas[i] = new Station(train_no_arg, k[train_num][1], 1, k[train_num][2], k[train_num][3], d[train_num][4]);
					}
					else {
						if (d[train_num][5 * i - 3] != "发站") {
							if (d[train_num][5 * i - 3] > d[train_num][5 * i + 2]) day_arg++;
						}
						else {
							if (d[train_num][5 * i - 2] > d[train_num][5 * i + 2]) day_arg++;
						}
						stas[i] = new Station(train_no_arg, d[train_num][5 * i + 1], day_arg, d[train_num][5 * i + 2], d[train_num][5 * i + 3], d[train_num][5 * i + 4]);
					}
					break;
				case "C":
					if (i == 0) {
						stas[i] = new Station(train_no_arg, c[train_num][1], 1, c[train_num][2], c[train_num][3], c[train_num][4]);
					}
					else {
						if (c[train_num][5 * i - 3] != "发站") {
							if (c[train_num][5 * i - 3] > c[train_num][5 * i + 2]) day_arg++;
						}
						else {
							if (c[train_num][5 * i - 2] > c[train_num][5 * i + 2]) day_arg++;
						}
						stas[i] = new Station(train_no_arg, c[train_num][5 * i + 1], day_arg, c[train_num][5 * i + 2], c[train_num][5 * i + 3], c[train_num][5 * i + 4]);
					}
					break;
				case "P":
					if (i == 0) {
						stas[i] = new Station(train_no_arg, p[train_num][1], 1, p[train_num][2], p[train_num][3], p[train_num][4]);
					}
					else {
						if (p[train_num][5 * i - 3] != "发站") {
							if (p[train_num][5 * i - 3] > p[train_num][5 * i + 2]) day_arg++;
						}
						else {
							if (p[train_num][5 * i - 2] > p[train_num][5 * i + 2]) day_arg++;
						}
						stas[i] = new Station(train_no_arg, p[train_num][5 * i + 1], day_arg, p[train_num][5 * i + 2], p[train_num][5 * i + 3], p[train_num][5 * i + 4]);
					}
					break;
				default:
					break;
			}
		}
		var trainObj = new Train(train_no_arg, stas);
		return trainObj;
	}
	else return null;
}
function Station(train_no, sta_name, days, arrv_time, dep_time, wait_min) {
	this.train_no = train_no; // 车次
	this.sta_name = sta_name; // 站名
	this.days = days; // 相对于始发站是第几天
	this.arrv_time = (arrv_time == "发站") ? "null" : arrv_time; // 到点
	this.dep_time = (dep_time == "终点") ? "null" : dep_time; // 开点
	this.wait_min = (wait_min == "----") ? "null" : wait_min; // 停留分钟数
}
function getStaByTrainnoAndStaname(train_no, name) {
	var trainArg = getTrainByTrainNo(train_no);
	for (var x1 = 0; x1 < trainArg.stations.length; x1++) {
		if (trainArg.stations[x1].sta_name == name) break;
	}
	return trainArg.stations[x1];
}