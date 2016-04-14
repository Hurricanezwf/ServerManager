1.添加服务器组时,需要在页面添加html代码,并且在cfg.ini里添加相应的组配置
2.修改/etc/postfix/main.cfg的inet_protocols=inet4(貌似不需要了)
3.预先到config.ini中配置好邮件的信息, 发邮件时最好用163邮箱, QQ邮箱有问题, 发不了邮件
4.发布的时候务必保证cache/目录的权限是777, 目录里文件内容清空, 文件权限改为666
