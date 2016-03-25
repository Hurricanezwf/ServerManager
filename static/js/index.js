$(".leftsidebar_box dt").css({"background-color":"#3992d0"});
$(function(){
	$(".leftsidebar_box dd").hide();
	$(".leftsidebar_box dt").click(function(){
        $(".leftsidebar_box dt").attr("class", "dt_inactive");
		$(this).attr("class", "dt_active");
		$(this).parent().find('dd').removeClass("menu_chioce");
		$(".menu_chioce").slideUp(); 
		$(this).parent().find('dd').slideToggle();
		$(this).parent().find('dd').addClass("menu_chioce");
	});


    $(".status").click(function(){
        var group_name = $(this).parent().parent().attr("class");    
        $("iframe").attr("src", "status.php?group="+group_name);
    });

})


$(document).ready(function(){
    setInterval("query_all_server_status()", 5000);
    setInterval("update_status()", 5000);
});
    

function query_all_server_status() {
    $("audio").attr("src", "");
    $(".leftsidebar_box dt").each(function(){
        $(this).removeClass("dump");         
    });

    $.get('/ServerManager/controllers/status_info.php',
            function(data) {
                var state = JSON.parse(data);
                var isAlarm = false;
                for (server_group in state) {
                    /*server_state = state[server_group]; 
                    for (server in server_state) {
                        var single_server_state = server_state[server];
                        if (single_server_state <= 0) {
                            alert_server_dump(server_group);
                            isAlarm = true;
                        }
                        break;
                    }*/
                    alert_server_dump(server_group);
                    isAlarm = true;
                }

                if (isAlarm) {
                    $("audio").attr("src", "/ServerManager/static/sound/alarm.mp3");
                }
            }
          );
}

function update_status() {
    var active_server_group = $(".menu_chioce").first().parent().attr("class");
    var data_show = $(".status_show").attr("src");
    var is_update_status = false;
    if (data_show != null) {
        is_update_status = data_show.match("status") != null ? true:false;
    }

    if (is_update_status) {
        $(".status_show").attr("src", "status.php?group="+active_server_group);
    }
}


function alert_server_dump(server_group) {
    $(".leftsidebar_box dl").each(function(){
        if($(this).attr("class") == server_group) {
           $(this).children("dt").addClass("dump"); 
        }
    });
}
