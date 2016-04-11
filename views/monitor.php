<!DOCTYPE html>
<html lang="zh-CN">
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
                            echo "<li data-hostid='$host_id'><a href='#'>$host_name</a></li>";
                        }
                        ?>
                    </ul>
                </div>
                
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Dashboard</h1>
                    <?php require_once 'show_status.php';?>
                    
                    <!--div class="row" data-hostid='host1'>
                        <div class="col-sm-12 col-md-12">
                            <span class="server_title">上海1服</span>
                            <span class="glyphicon glyphicon-menu-down pull-right"></span>
                            <div id="_1_3" class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                          <th>process</th>
                                          <th>STATE</th>
                                          <th>CPU(%)</th>
                                          <th>MEMORY(MB)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          <td>login_server</td>
                                          <td>1</td>
                                          <td>1</td>
                                          <td>120</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                          <hr>
                        </div>
                    </div-->
                </div>
            </div>
        </div>
    </body>
</html>
