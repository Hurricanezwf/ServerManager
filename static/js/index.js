$(document).ready(function(){
    $(".left_nav a").click(function(){
        var choice = $(this).html();
        switch (choice) {
        case "状态监控":
            $(".frame").attr("src", "/ServerManager/controllers/status.php");
            break;
        case "编译":
            $(".frame").attr("src", "/ServerManager/controllers/build.php");
            break;
        case "启动":
            $(".frame").attr("src", "/ServerManager/controllers/start.php");
            break;
        case "停止":
            $(".frame").attr("src", "/ServerManager/controllers/stop.php");
            break;
        default:
            alert("unkown choice[" + choice + "]");
            break;
        }
    });
});
