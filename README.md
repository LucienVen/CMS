# 基于Slim的基础MVC框架
the slime-based MVC framework



>Slim 是一款 PHP 微框架，在它的核心，Slim 是一个调度程序，它接收一个 HTTP请求，调用一个适当的回调例程，然后返回一个 HTTP 响应。在官方示例中，并没有体现到在分层架构下完成业务需求的清晰明了，所以我基于 Slim 框架构建了一个基础的 MVC框架（其实没有View \\笑），结合一些 composer 组件，进行面向对象方面的学习与一些基础功能实现。
>
>
>
>注：该仓库命名为 **CMS** 的原因是原意准备搭建一个CMS服务的应用接口程序（也是接下来的开发目标）





### 框架结构

```bash
# 基础项目框架
CMS
├── App														-- 应用程序
├── Core													-- 框架核心类
├── File													-- 上传文件文件夹
├── Log														-- Monolog日志存放目录
├── README.md									
├── composer.json	
├── composer.lock
├── help													-- 文档信息以及数据库文件
├── public													-- 框架入口
└── vendor													-- composer组件库

# 框架核心类
./Core
├── Action.php												-- 行为控制类基类
├── Autoload.php											-- 自动加载类
├── Config.php												-- 配置文件读取加载类
├── Init.php												-- 
├── Middleware.php											-- 中间件基类
├── Model.php												-- 模型类基类
└── Start.php												-- 应用程序实例类

# 应用程序
./App
├── Action													-- 行为控制器(Controller层)
│   ├── Auth.php											
│   ├── Test.php
│   └── User.php
│   └── ...
├── Common													-- 公共函数目录
│   └── GetInfo.php
│   └── ...
├── Middleware												-- 中间件
│   ├── JWTMiddleware.php
│   ├── PermissionMiddleware.php
│   ├── SlimJWTMiddleware.php
│   └── TestMiddleware.php
│   └── ...
├── Model													-- 模型层(Model层)
│   ├── Auth.php
│   ├── Test.php
│   └── User.php
│   └── ...
├── Route.php												-- 路由文件(TODO 细化拓展)
├── Dependencies.php										-- 自定义容器服务
└── Settings.php											-- 应用程序配置文件


# 入口文件目录
./public
├── index.php												-- 入口文件
└── start.php												-- 启动文件
```

### 综述

> 框架加载与功能实现流程

### 基础使用

> 描述如何编写一个API接口

### 目前已实现功能（API 文档）

#### 用户系统（登录、注册、注销）

#### 权限系统（权限验证）



### 未实现功能以及需要完善的内容

- [ ] 自定义错误处理类
- [ ] 统一程序状态处理码
- [ ] 使用 [Eloquent](https://laravel.com/docs/5.1/eloquent) ORM
- [ ] 公共函数设计

