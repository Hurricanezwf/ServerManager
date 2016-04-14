function BindEvent() {
    $("div.server_row").hide();
    $("div.container-fluid").delegate('li', 'click', function(){
        $("li").each(function(){
            $(this).removeClass("active");
        });
        $(this).addClass("active");

        $("div.server_row").hide();
        var host_id = $(this).attr("id");
        $("div."+host_id).show();
    });

    
    $("div.table-responsive").hide();
    $("div.container-fluid").delegate('span.glyphicon-menu-up', 'click', function(){
        $(this).removeClass("glyphicon-menu-up");
        $(this).addClass("glyphicon-menu-down");
        $(this).siblings("div.table-responsive").slideUp("slow");
    });
    $("div.container-fluid").delegate('span.glyphicon-menu-down', 'click', function(){
        $(this).removeClass("glyphicon-menu-down");
        $(this).addClass("glyphicon-menu-up");
        $(this).siblings("div.table-responsive").slideDown("slow");
    });


    $("div.container-fluid").delegate('input.mui-switch', "click", function(){
        $(this).attr("disabled", "true");
        var tableid = $(this).parent().parent().siblings("div.table-responsive").attr("id");
        if (tableid == null) {
            alert("get tableid failed!");
            return;
        }

        var param = tableid.split("_");
        var op_type;
        var is_checked = $(this).attr("checked");
        if ("checked" == is_checked) {
            op_type = "stop";
            $(this).removeAttr("checked");
        } else if (null == is_checked){
            op_type = "start";
            $(this).attr("checked", "checked");
        }

        $(this).removeAttr("disabled");
        $.post("/ServerManager/controllers/start_stop.php",
            {
                hostid  : param[1],
                groupid : param[2],
                optype  : op_type
            },
            function(data) {
                if (data == "failed") {
                    alert("failed");
                }
            }
        );
        sleep(1);
    });
}



// 开启定时器监控各组服务器
function StartMonitor() {
   $.get("/ServerManager/controllers/status_info.php",
             function(data) {
                 var res = JSON.parse(data);
                 if (res != null && res != "") {
                    var dump_tableid = new Array();
                    var is_ok = false;
                    for (var group in res) {
                        var group_data = res[group];
                        var host_id  = group_data['host_id'];
                        var group_id = group_data['group_id'];
                        var status_info = group_data['status_info'];
                        var is_server_on = 0;
                       
                        var table_id = "_" + host_id + "_" + group_id;
                        for (var server in status_info) {
                            var single_server_data = status_info[server];
                            for (var server_name in single_server_data) {
                                var detail = single_server_data[server_name];
                                $("#"+table_id).children("table").children("tbody").children("tr").children("td.name").each(function(){
                                    if ($(this).html() == server_name) {
                                        $(this).siblings("td.state").html(detail['state']);
                                        if (parseInt(detail['state']) != 1) {
                                            dump_tableid[table_id] = 1;
                                        } else {
                                            is_server_on = 1;
                                        }
                                        
                                        $(this).siblings("td.cpu").html(detail['cpu']);
                                        $(this).siblings("td.memory").html(detail['memory']);
                                        is_ok = true;
                                    }
                                });
                            }
                        }

                        // 修改开关状态
                        if (is_server_on > 0) {
                            $("#"+table_id).siblings("span.server_switch").find("input").attr("checked", true);
                        } else {
                            $("#"+table_id).siblings("span.server_switch").find("input").attr("checked", false); 
                        }
                    }

                    // 修改导航栏警报显示
                    $("span.server_title").removeClass("server_dump");
                    $("li").children("a").css("color", "");
                    $("audio").attr("src", "");
                    var is_audio = false;
                    for (var tableid in dump_tableid) {
                        $("#"+tableid).siblings("span.server_title").addClass("server_dump");
                        var hg = tableid.split("_");
                        var host_id = "host"+hg[1];
                        $("#"+host_id).children("a").css("color", "red");;
                        is_audio = true;
                    }
                    if (is_audio) {
                        $("audio").attr("src", "../static/sound/alarm.mp3");
                    }
                 }

                 if (is_ok) {
                     $("h1.page-header").css("color", "green"); 
                 } else {
                    $("h1.page-header").css("color", "red");
                 }
             }  
        );
}
