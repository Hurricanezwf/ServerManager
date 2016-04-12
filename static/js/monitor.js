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
                                        }
                                        
                                        $(this).siblings("td.cpu").html(detail['cpu']);
                                        $(this).siblings("td.memory").html(detail['memory']);
                                        is_ok = true;
                                    }
                                });
                                 
                            }
                        }
                    }

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
