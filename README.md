部署方式:
   1. 先到项目的conf目录下配置email_receivers和服务器组相关的信息(cfg.ini和servers.xml)
   2. 将整个项目文件拷贝到nginx的html目录下
   3. 执行项目文件中的init.sh(这个主要是将cache的目录权限改为777, 以及里面的status.cache文件权限改为666)

备注:
1.修改/etc/postfix/main.cfg的inet_protocols=inet4(貌似不需要了)
2.SMTP服务器最好用163邮箱的, QQ邮箱有问题, 发不了邮件
3.发布的时候务必保证cache/目录的权限是777, 目录里文件内容清空, 文件权限改为666
