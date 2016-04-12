<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Cache-control" content="no-cache" />
        <meta http-equiv="Cache" content="no-cache" />
        
        <title>monitor</title>

        <link href="../static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../static/css/dashboard.css" rel="stylesheet">
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="../static/js/jquery-2.2.1.min.js"></script>
        <script src="../static/bootstrap/js/bootstrap.min.js"></script>
        <script src="../static/js/monitor.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                BindEvent();
                setInterval("StartMonitor()", 3000);
            });
        </script>
    </head>
    
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Server Monitor</a>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <?php
                        $xml = simplexml_load_file("../conf/servers.xml");
                        foreach ($xml->children() as $host) {
                            $host_id   = "host" . $host->host_id;
                            $host_name = $host->host_name;
                            echo "<li id='$host_id'><a href='#'>$host_name</a></li>";
                        }
                        ?>
                    </ul>
                </div>
                
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Dashboard</h1>
                    <?php require_once 'show_status.php'; ?>
                </div>
            </div>
        </div>
        <audio src="" autoplay="autoplay" display="none"></audio>
    </body>
</html>
