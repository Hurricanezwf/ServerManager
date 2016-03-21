$(document).ready(function(){
    $(".left_nav a").click(function(){
        var choice = $(this).html();
        switch (choice) {
        case "状态监控":
            $(".frame").attr("src", "/status");
            break;
        case "编译":
            $(".frame").attr("src", "/build");
            break;
        case "启动":
            $(".frame").attr("src", "/start");
            break;
        case "停止":
            $(".frame").attr("src", "/stop");
            break;
        default:
            alert("unkown choice[" + choice + "]");
            break;
        }
    });
});
