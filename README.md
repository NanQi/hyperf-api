# 后端API接口框架

公司项目后端API接口使用Hyperf开发，且在组件生态完善下保持Hyperf最新版本；

## 项目概述

* 产品名称：后端接口
* 项目代号：api

## 运行环境要求

- Nginx OpenResty 1.15+ （如需要使用到 Nginx）
- PHP >= 7.3 （最好使用最新版PHP）
- Swoole PHP 扩展 >= 4.4，并关闭了 Short Name
- OpenSSL PHP 扩展
- JSON PHP 扩展
- PDO PHP 扩展 （如需要使用到 MySQL 客户端）
- Redis PHP 扩展 （如需要使用到 Redis 客户端）
- Protobuf PHP 扩展 （如需要使用到 gRPC 服务端或客户端）

## 服务器架构说明

![架构](https://cdn.learnku.com/uploads/images/201705/20/1/1G6aQPAZym.png)

## 开发环境安装

团队成员本地开发环境**推荐**使用 [Laravel Homestead](https://learnku.com/docs/laravel/5.8/homestead) 或 docker。

不建议使用Windows开发环境。

### 安装前置

如果还没有安装 Composer，在 Linux 和 Mac OS X 中可以运行如下命令：
~~~
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
~~~

由于众所周知的原因，国外的网站连接速度很慢。因此安装的时间可能会比较长，我们建议通过下面的方式使用国内镜像。

打开控制台（Linux、Mac 用户）并执行如下命令：
~~~
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
~~~

### 安装依赖
~~~
composer install
~~~

### 初次安装
~~~
php -r "file_exists('.env') || copy('.env.example', '.env');"
php bin/hyperf.php migrate
~~~

### 配置Homestead
~~~
folders:
    - map: ~/path/api/ # 你本地的项目目录地址
      to: /home/vagrant/api

sites:
    - map: api.test
      to: /home/vagrant/api

databases:
    - api
~~~
### 配置 Http 代理（通过IP访问跳过此步骤）
~~~
# 至少需要一个 Hyperf 节点，多个配置多行
upstream hyperf {
    # Hyperf HTTP Server 的 IP 及 端口
    server 127.0.0.1:9501;
    server 127.0.0.1:9502;
}

server {
    # 监听端口
    listen 80; 
    # 绑定的域名，填写您的域名
    server_name proxy.hyperf.io;

    location / {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点  
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        # 转发Cookie，设置 SameSite
        proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";

        # 执行代理访问真实服务器
        proxy_pass http://hyperf;
    }
}

~~~
修改本地HOST文件，`api.test`域名指向Homestead配置IP

### 启动服务(生产环境禁止使用此方式)
~~~
php watch
~~~

然后就可以在浏览器中访问

~~~
http://192.168.10.10:9501
~~~

也可以自行配置apache或nginx等web服务器

Homestead
~~~
http://api.test
~~~

看到`Welcome to our API`表示安装成功

### 配置队列

根据实际业务选择Redis异步队列或AMQP,或两者结合使用
参考文档 [异步队列](https://hyperf.wiki/#/zh-cn/async-queue)
### 配置任务调度
-  方式一

~~~
crontab -e
~~~

尾部增加配置
~~~
* * * * * cd /home/vagrant/code/hyperf-api && php bin/hyperf.php demo:command >> /dev/null 2>&1
~~~
对应文件：app/Command/TestCommand.php
-  方式二
config/autoload/crontab.php
-  方式三
App\Task\DemoTask
### 更新框架
~~~
composer update 
~~~

### 升级框架

按照官方文档指引完成升级

框架始终保持官方master版本（大改动除外）

## 在线手册

+ [官方在线文档](https://hyperf.wiki/#/)

## 目录结构

目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─app                   应用目录
│  ├─Aspect            切面目录
│  ├─Controller        控制器目录
│  ├─Exceptions        异常处理器
│  ├─Event             事件
│  ├─Helper            帮助类
│  ├─Listener          监听类
│  ├─Middleware        中间件目录
│  ├─Model             模型目录 
│  ├─Process           队列
│  ├─Request           请求验证
│  ├─Service           业务逻辑目录
├─bin                   框架核心入口
├─config                应用配置目录
├─migrations            数据结构迁移目录
├─runtime               缓存文件
├─storage               Storage目录
├─test                  单元测试目录
├─vendor                依赖包目录
├─.env                  环境配置文件
├─.env.example          环境配置文件模板
├─composer.json         composer 定义文件
├─Dockerfile            docker文件
├─openapi.json          接口文档
├─phpunit.xml           单元测试配置文件
├─README.md             README 文件
├─watch                 热重载
~~~


### 配置swagger文档

1、打开项目配置文件
sudo vim /etc/nginx/sites-available/hyperf-api.test

2、增加如下代码
location /file/ {
          alias /home/vagrant/Code/hyperf-api/doc/;
      }
      
3、重启nginx
sudo nginx -s reload

4、生成接口文档
php bin/hyperf.php swagger:gen -o ./doc/

5、访问接口文档
项目/file/dist/index.html