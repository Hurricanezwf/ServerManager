function BindEvent() {
    $("div.container-fluid").delegate('li', 'click', function(){
        $("li").each(function(){
            $(this).removeClass("active");
        });
        $(this).addClass("active");
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