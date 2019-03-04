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
├── App									-- 应用程序
├── Core								-- 框架核心类
├── File								-- 上传文件文件夹
├── Log									-- Monolog日志存放目录
├── README.md									
├── composer.json	
├── composer.lock
├── help								-- 文档信息以及数据库文件
├── public								-- 框架入口
└── vendor								-- composer组件库

# 框架核心类
./Core
├── Action.php								-- 行为控制类基类
├── Autoload.php							-- 自动加载类
├── Config.php								-- 配置文件读取加载类
├── Init.php								-- 核心类
├── Middleware.php							-- 中间件基类
├── Model.php								-- 模型类基类
└── Start.php								-- 应用程序实例类

# 应用程序
./App
├── Action								-- 行为控制器(Controller层)
│   ├── Auth.php											
│   ├── Test.php
│   └── User.php
│   └── ...
├── Common								-- 公共函数目录
│   └── GetInfo.php
│   └── ...
├── Middleware								-- 中间件
│   ├── JWTMiddleware.php
│   ├── PermissionMiddleware.php
│   ├── SlimJWTMiddleware.php
│   └── TestMiddleware.php
│   └── ...
├── Model								-- 模型层(Model层)
│   ├── Auth.php
│   ├── Test.php
│   └── User.php
│   └── ...
├── Route.php							-- 路由文件(TODO 细化拓展)
├── Dependencies.php						-- 自定义容器服务
└── Settings.php						-- 应用程序配置文件


# 入口文件目录
./public
├── index.php							-- 入口文件
└── start.php							-- 启动文件
```

### 综述

> 框架加载与功能实现流程
>
> 对于Slim框架，官方已提供“三大件”功能，分别是：**请求与响应，路由分发，中间件与容器**。这也是Slim官方文档内容的主要部分。个人理解请求与响应、路由分发，已是php应用程序中最基础又最重要的部件了。而中间件与容器，则是提供给开发者更便捷的、更面向对象的、提高开发效率与代码质量的部件。
>
> 对于一个HTTP请求，PHP本质上的工作就是**接收并解析请求，路由分发（调度），调用类、方法、函数处理请求，然后返回响应**。
>
> 

按一般MVC框架的加载流程，可以简述为：

1. 入口文件，定义常量
2. 加载异常处理类（未完成）
3. 加载框架核心类，加载自动加载类
4. 读取配置文件，获取应用程序实例
5. 注册容器服务，中间件，路由
6. 启动应用程序
7. 路由回调



简单图示：

![Framework](https://i.loli.net/2019/03/04/5c7cdb9a68139.png)

### 基础使用

> 描述如何使用目前的框架编写一个API接口
>
> 按照开发者的开发需要来描述。
>
> 一般业务开发，主要需要编写的就是路由，中间件，容器服务，控制器类与模型类

**A: 如权限检查，token校验这样的适用于某个时刻/切片的需求**

Q: 编写中间件。可在/App/Middleware目录下，添加中间件(.php)文件，推荐使用 __invoke

***

**A: 需要在应用程序的不知名的各处使用的服务，如数据库，redis连接（未实现），Monolog日志记录等服务**

Q: 编写容器服务。框架加载的容器服务文件位于 /App/Dependencies.php，使用格式如

​	```$container['service_name'] = function($c){...}``` 使用闭包。可存储服务实例，可在各处调用。

***

**A: 一般业务逻辑，CURD操作等**

Q: /App/Action目录编写**控制器类文件**，可加载Action基类， 以提供某些方法（或在基类中添加某些方法）

​	Action控制器类中的方法建议带有```$request, $response, $args```这三个参数，因为不知名原因，貌似Slim核心没有对容器\$container进行单例，导致在Action基类获取的```$request, $response```应该是默认请求与响应的clone，如我之前在JWT中间件中进行把用户数据存储请求属性的操作时，并不能通过基类中的$this->_request属性获取，但是方法定义为function function_name(\$request){…}后，能从该\$request中获取先前存储的请求属性。**简单而言，不要太过相信Action基类定义的属性，可能会出错，解决方法...emmmmmmmmm**

```php
// e.g.
class className
{
    public function funcName($request, $response, $args)
    {
        ...
    }
}
```

​	**模型类**在/App/Model目录下编写，应继承Model基类（原因如上），在对应Action定义方法中，



**注：应用程序目前使用PDO做数据库连接**

### 目前已实现功能（API 文档）

#### 用户系统（登录、注册、注销）

##### 接口使用

***

**登录 /login**

路由：/login

请求方法：POST

请求参数：(string)email, (string)password

返回信息：(JSON) {"code":201,"msg":"login success"}

​响应头字段**Authorization→**eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJMdWNpZW4iLCJhdWQiOiJodHRwOlwvXC9oZWxsb3dvcmxkLmNvbSIsImV4cCI6MTU1MTcyODMzMiwiZGF0YSI6eyJpZCI6IjciLCJlbWFpbCI6ImxpYW5nbGlhbmcudG9vQGdtYWlsLmNvbSIsInNlY3JldCI6ImxpYW5nbGlhbmcudG9vQGdtYWlsLmNvbS41YzdjMmQ0YzJiZWFiIn19.rS9fMx2VfVPkHZIix0vxQO9p6gNQ7n7PiwFMchgh7qM

***

**注册 /registered**

路由：/registered

请求方法：POST

请求参数：(string)email, (string)password, (string)password_again

返回信息：{"code":261,"msg":"registered success"}



***

**注销 /logout**

路由：/logout

请求方法：DELETE

请求参数：无

返回信息：{"code":252,"msg":"logout success"}

**请求头字段（必须）：Authorization** -> token

***

##### 数据库设计

| name     | type         | key      | desc                         |
| -------- | ------------ | -------- | ---------------------------- |
| id       | int          | pk       | 用户id                       |
| email    | varchar(50)  | [unique] | 用户email，用于登录          |
| password | varchar(255) |          | 密码                         |
| nickname | varchar(30)  |          | 昵称                         |
| phone    | char(11)     |          | 手机号码                     |
| secret   | varchar(50)  | [unique] | 用户登录token标志            |
| status   | tinyint(1)   |          | 账户状态（0-未启用，1-正常） |



#### 权限系统（权限验证）

权限验证的前提是必须先进行登录操作。前端发起请求的时候，把登录时生成的token，添加到请求头字段Authorization。在JWT验证通过后，获取存储在token中的用户信息（如id），然后在权限验证中间件中进行权限校验。

本系统的权限系统参考RBAC（Role-Based Access Control，基于角色的访问控制）的实现，用户通过角色与权限进行关联。

##### 使用方法

```php
// e.g.
$app->get('/path', '\App\Action\Test:path')
    ->add(new App\Middleware\PermissionMiddleware($container)) // 权限验证中间件
    ->add(new App\Middleware\JWTMiddleware($container)); // JWT中间件
```

##### 数据库设计

> 以下简略列出权限系统设计的相关数据表字段

用户表 user

| name  | desc     |      |
| ----- | -------- | ---- |
| id    | 用户id   |      |
| email | 用户邮箱 |      |
| ...   |          |      |

角色表 role

| name | desc                           |      |
| ---- | ------------------------------ | ---- |
| id   | 角色id                         |      |
| name | 权限角色名称（如管理员，运维） |      |
| desc | 角色描述                       |      |
| ...  |                                |      |

权限节点表 permission

| name | desc                                               |      |
| ---- | -------------------------------------------------- | ---- |
| id   | 权限节点id                                         |      |
| path | 控制路径（如/action/test/path, /action/user/info） |      |
| name | 节点名称                                           |      |
| ...  |                                                    |      |

用户-角色关联表 role_user

| name    | desc     |      |
| ------- | -------- | ---- |
| id      | 关联表id |      |
| role_id | 角色id   |      |
| user_id | 用户id   |      |

访问权限表 access

| name      | desc             |      |
| --------- | ---------------- | ---- |
| id        |                  |      |
| role_id   | 角色id           |      |
| node_id   | 权限节点id       |      |
| **value** | **权限允许分值** |      |
| ...       |                  |      |

##### 权限分值设计与说明

> 准确来说，这样的分值设计，有偷懒嫌疑（笑

我们回想Linux系统的权限设计，例如 chmod 777， 或者755 之类的语句是不是会经常出现？是的，1、2、4这三个数字，任意两个不同的数字相加，得出的值不会相同。这是一系列特殊的数字。

然后回想HTTP请求方法中，最常用的是那几个呢？其实基于公司业务，有些公司在请求上只使用GET, POST两种方法。但是如果我们想要设计得更加“moderm”一点，例如参考RESTful风格的API设计，这时我们需要用到的请求方法（或者说**HTTP动词**）就会有 **GET, POST, PUT, PUTCH, DELETE**，这基本的常用的五种（当然还有HEAD, OPTIONS这些）。

这是回到这部分最开头的说明，"有偷懒嫌疑"这句…….又希望能使用RESTful API设计风格.....

本权限分值的设计，是**人为约定了系统使用的请求方法与其对应分值**如下所列：

*  GET（查询方法）分值为 1
* POST / PUT / PATCH （新增/更新方法）分值为 2
* DELETE（删除方法）分值为 4

又在配置文件中添加了**不同请求方法对应的所能列举出来的所有分值**，(更咸鱼地选择性忽略了PUT方法)

```php
'permission' => [
        'DELETE' => [4, 6, 5, 7],
        'POST'  => [2, 6, 3, 7],
        'PATCH' => [2, 6, 3, 7],
        'GET'   => [1, 3, 5, 7],
    ],
```



可能上面描述得不是很好，那我们来完整走一遍权限验证流程

1. 带有Authorization字段的请求头，进入JWT中间件，
2. JWT验证成功，并且把token中带有的用户信息（如id），存储到请求属性里
3. Permission中间件从请求属性获取**用户id**
4. 根据请求头$request中的 'route' 属性获取**回调方法路径path与请求方法Method**
5. 数据库操作
   * 根据用户id，获取用户对应的角色id => user_role表 => user_id
   * 根据 path 获取权限节点id => permission表 => node_id
   * 根据用户id 和权限节点id，获取权限访问分值 => access表 => value
6. 根据请求方法，获取配置文件中，该方法能被允许通过的分值
   * 如请求方法为POST，获取access数据表中**该权限节点的分值为 x**，从配置文件中获取的该方法能被允许的**分值数组为$pass**
   * 进行 **in_array(x, $pass)** 的判断，为真则通过，反之则拒绝。



##### 优缺点

缺点：这样设计存在的问题是，权限不够细分，违背RBAC模型的最小权限原则。

优点：简单（笑



### 未实现功能以及需要完善的内容

- [ ] 自定义异常处理类
- [ ] 完善数据校验
- [ ] 统一程序状态返回码以及返回信息
- [ ] 使用 [Eloquent](https://laravel.com/docs/5.1/eloquent) ORM
- [ ] 公共函数设计
- [ ] **Restful风格API设计**





### 参考链接

[Slim 中文文档](https://slimphp.app/docs/)

[Slim 英文文档 - (内容比中文文档更多)](https://www.slimframework.com/docs/v3/start/installation.html)

[firebase/php-jwt](https://packagist.org/packages/firebase/php-jwt)

[RESTful API 设计指南 - 阮一峰](http://www.ruanyifeng.com/blog/2014/05/restful_api.html)

[RBAC模型](http://www.mossle.com/docs/auth/html/ap08-rbac.html)

[PHP RBAC权限管理](https://my.oschina.net/programs/blog/1648205)

...