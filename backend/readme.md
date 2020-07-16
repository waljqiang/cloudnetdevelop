
## About Laravel

此Laravel是基于Laravel框架原有基础上，实现了Laravel框架模块化开发。

## 代码目录

```

--backend
    --app
    --bootstrap
    --config
    --database
    --Modules
        --Test
            --Assets
            --Config
            --Console
            --Database
            --Emails
            --Entities
            --Events
            --Http
                --Controllers
                --Middleware
                --Requests
                routes.php
            --Jobs
            --Listeners
            --Notifications
            --Providers
            --Repositories
            --Resources
            --Services
            --Tests
            composer.json
            module.json
            start.php
    --public
    --resources
    --routes
    --Services
    --storage
    --tests
    --vendor
    .env
    .env.example
    .gitattributes
    .gitignore
    artisan
    composer.json
    composer.lock
    package.json
    phpunit.xml
    readme.md
    server.php
    webpack.mix.js
--frontend
```
    

* 目录说明

| 目录| 描述|
|:---:|:---:|
|app|应用核心代码|
|bootstrap|框架启动、自动加载、路由及服务缓存|
|config|配置文件|
|database|数据迁移及填充|
|Modules|应用模块|
|public|入口文件及前端资源|
|Resources|视图、前端原生资源、语言|
|storage|编译过的Blade模板、基于文件的session、文件缓存，以及其它由框架生成的文件|
|tests|自动化测试|
|Console| Artisan 命令|
|routes|路由|
|Middleware|中间件|
|Requests|校验器|
|Controllers|控制器|
|Services|业务逻辑层|
|Repositories|数据逻辑层|
|Entities|数据层|
|Events|事件类|
|Jobs|队列任务|
|Listeners|事件监听器|
|Notifications|通知|
|Providers|服务提供者|

-----

## 添加Hashids服务提供者，可以实现对数字id的加密处理

## auth

#### 使用tymon/jwt-auth实现多点认证登录

* 安装与配置

    1、composer require tymon/jwt-auth 1.0.*@dev

    2、在配置文件app.php中添加Tymon\JWTAuth\Providers\LaravelServiceProvider::class服务提供者,

    3、发布配置文件，执行php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

    4、生成加密秘钥，执行php artisan jwt:secret

    5、更新user模型，这里使用的User模型为App\Models\User模型，具体请看代码

    6、修改配置文件auth.php，配置guard中的api的driver为jwt，provider为users，provider中的users的model为App\Models\User::class

    7、涉及中间件为RequestLogin中间件，具体请查看代码。

* JWTAuth

    JWTAuth::parseToken()->方法()一般都可以替换成auth()->方法

    * token的生成
    
        1、attempt，根据用户账密生成一个token

        ```
            $credentials = $request->only('account', 'password');
            $token = JWTAuth::attempt($credentials)；
        ```

        2、fromUser or fromSubject,后者是前者的别名

        ```
            $user = User::find(1);
            $token = JWTAuth::fromUser($user);
        ```
    
    * token的控制
    
        1、refresh

        ```
            JWTAuth::parseToken()->refresh();
        ```

        2、invalidate

        ```
            JWTAuth::parseToken()->invalidate();
        ```

        3、check

        ```
            if(JWTAuth::parseToken()->check()) {
                dd("token是有效的");
            }
        ```

    * token解析

        1、authenticate or toUser or user;这三个效果是一样的，toUser 是authenticate 的别名，而 user 比前两者少一个 user id 的校验，但并没有什么影响.

        ```
            $user = JWTAuth::parseToken()->toUser();
        ```

        2、parseToken,从 request 中解析 token 到对象中，以便进行下一步操作

        ```
            JWTAuth::parseToken();
        ```

        3、getToken,从 request 中获取 token

        ```
            $token = JWTAuth::getToken();
        ```

    * 载荷控制

        1、customClaims or claims，设置载荷的 customClaims 部分。后者是前者的别名

        ```
            $customClaims = ['sid' => $sid, 'code' => $code];
            $credentials = $request->only('email', 'password');
            $token = JWTAuth::customClaims($customClaims)->attempt($credentials);
        ```

        2、getCustomClaims,获取载荷的 customClaims 部分，返回一个数组

        ```
            $customClaims = JWTAuth::parseToken()->getCustomClaims();
        ```

        3、getPayload or payload,获取所有载荷，三个都是一样的，最后一个一般用来检验 token 的有效性

        ```
            $payload = JWTAuth::parseToken()->payload();

            // then you can access the claims directly e.g.
            $payload->get('sub'); // = 123
            $payload['jti']; // = 'asfe4fq434asdf'
            $payload('exp') // = 123456
            $payload->toArray(); // = ['sub' => 123, 'exp' => 123456, 'jti' => 'asfe4fq434asdf'] etc
        ```

        4、getClaim,获取载荷中指定的一个元素

        ```
            $sub = JWTAuth::parseToken()->getClaim('sub');
        ```

* JWTGuard

    这个 Facade 主要进行载荷的管理，返回一个载荷对象，然后可以通过 JWTAuth 来对其生成一个 token

    ```
        / 载荷的高度自定义
        $payload = JWTFactory::sub(123)->aud('foo')->foo(['bar' => 'baz'])->make();
        $token = JWTAuth::encode($payload);

        $customClaims = ['foo' => 'bar', 'baz' => 'bob'];
        $payload = JWTFactory::make($customClaims);
        $token = JWTAuth::encode($payload);
    ```

* 其他用法

    这里用 auth 的写法，因为 Laravel 有多个 guard，默认 guard 也不是 api ，所以需要写成 auth('api') 否则，auth() 即可.

    1、设置载荷

    ```
        $token = auth('api')->claims(['foo' => 'bar'])->attempt($credentials);
    ```

    2、显示设置 token

    ```
        $user = auth('api')->setToken('eyJhb...')->user();
    ```

    3、显示设置请求

    ```
        $user = auth('api')->setRequest($request)->user();
    ```

    4、重写有效时间

    ```
        $token = auth('api')->setTTL(7200)->attempt($credentials);
    ```

    5、验证账密是否正确

    ```
        $boolean = auth('api')->validate($credentials);
    ```

-----

#### 使用laravel/passport实现第三方认证

    目前，针对客户端认证方式做了修改，passport的部分类做了重写以适应需求.

* passport流程分析

    1、\Laravel\Passport\PassportServiceProvider服务提供者为核心,register方法如下:

    ```
        public function register()
        {
            $this->registerAuthorizationServer();

            $this->registerResourceServer();

            $this->registerGuard();
        }
    ```
    分别注册了认证服务、资源服务、认证guard，如果需要跟改不模式下的业务，可注册重新的相关grant

-----

    认证服务

    ```
        protected function registerAuthorizationServer()
        {
            $this->app->singleton(AuthorizationServer::class, function () {
                return tap($this->makeAuthorizationServer(), function ($server) {
                    $server->enableGrantType(
                        $this->makeAuthCodeGrant(), Passport::tokensExpireIn()
                    );

                    $server->enableGrantType(
                        $this->makeRefreshTokenGrant(), Passport::tokensExpireIn()
                    );

                    $server->enableGrantType(
                        $this->makePasswordGrant(), Passport::tokensExpireIn()
                    );

                    $server->enableGrantType(
                        new PersonalAccessGrant, new DateInterval('P1Y')
                    );

                    $server->enableGrantType(
                        new ClientCredentialsGrant, Passport::tokensExpireIn()
                    );

                    if (Passport::$implicitGrantEnabled) {
                        $server->enableGrantType(
                            $this->makeImplicitGrant(), Passport::tokensExpireIn()
                        );
                    }
                });
            });
        }
    ```
    主要启动各个oauth授权方式，实例化各个授权方式的类

    2、路由，Passport::routes();

    ```
        public static function routes($callback = null, array $options = [])
        {
            $callback = $callback ?: function ($router) {
                $router->all();
            };

            $defaultOptions = [
                'prefix' => 'oauth',
                'namespace' => '\Laravel\Passport\Http\Controllers',
            ];

            $options = array_merge($defaultOptions, $options);

            Route::group($options, function ($router) use ($callback) {
                $callback(new RouteRegistrar($router));
            });
        }
    ```

-----

    实际上执行了RouteRegistrar的all方法,分别注册了不同模式下的路由及简单页面管理路由，如果想更改路由可重新相关方法

    ```
        public function all()
        {
            $this->forAuthorization();
            $this->forAccessTokens();
            $this->forTransientTokens();
            $this->forClients();
            $this->forPersonalAccessTokens();
        }
    ```

* 重写类说明

    1、重写PassportServiceProvider为APP\Providers\PassportServiceProvider，以使用自己重写的以下类

    ```
        use App\Utils\Passport\Passport;
        use App\Utils\Passport\ClientCredentialsGrant;
        use App\Utils\Passport\TokenGuard;
        use App\Utils\Passport\ClientRepository;
        use App\Utils\Passport\TokenRepository;
    ```

    2、重写Passport为App\Utils\Passport\Passport;主要为使用重写的App\Utils\RouteRegistrar来实现路由的更改

    3、重写RouteRegistrar为App\Utils\Passport\RouteRegistrar来实现路由的更改

    4、重写TokenGuard为App\Utils\Passport\TokenGuard来实现token校验处理

    5、重写Clientrepository为App\Utils\Passport\ClientRepository以扩展client数据操作并且修正原客户端模式下没有refresh token问题

    6、重写TokenRepository为App\Utils\Passport\TokenRepository以扩展token数据操作

    7、重新AccessTokenRepository为App\Utils\Passport\AccessTokenRepository，增加查找无效access token方法、设置token失效方法

    8、重新RefreshTokenRepository为App\Utils\Passport\RefreshTokenRepository，增加设置refreh token无效方法


* 配置

    1、配置guard

        在config/auth.php的guards配置中增加如下guard

    ```
        'open' => [
            'driver' => 'passport',
            'provider' => 'users',
        ]
    ```

        App\Utils\Passport中，创建RouteRegistrar对象的时候指定使用open guard,

    ```
        public static function routes($callback = null, array $options = []){
            $callback = $callback ?: function ($router) {
                $router->all();
            };

            $defaultOptions = [
                'prefix' => 'oauth',
                'namespace' => '\Laravel\Passport\Http\Controllers',
            ];

            $options = array_merge($defaultOptions, $options);

            Route::group($options, function ($router) use ($callback) {
                $callback(new RouteRegistrar($router,'open'));
            });
        }

    ```

    2、配置服务提供者

        在config\app.php的服务提供者中添加App\Providers\PassportServiceProvider::class,

    3、注册Passport服务提供者

        AuthServiceProvider 的 boot 方法中调用 Passport::routes、Passport::tokensExpireIn、Passport::refreshTokensExpireIn;

    4、添加config\open.php配置文件

    ```
        return [
            'client_salt' => 'cloudnetlot',//客户端id加密salt
            'client_id_length' => 25,//客户端 id长度
            'client_id_header' => 'ycy',//客户端id头部
            'client_alphabet' => 'abcdefghijklmnopqrstuvwxyz',//客户端id取值范围
            'token_expire_in' => 120,//token过期时间，分钟
            'refresh_token_expire_in' => 30,//刷新token过期时间
            'scopes' => [
                'user-infos' => 'Get user infos',//获取用户信息
            ]//token作用域范围
        ];
    ```


* 客户端说明

    passport自己生产的客户端client_id为数据表自增id值，为了安全及修改方便，使用Hashids\Hashids对client_id进行加密

* 事件 

    1、在App\Providers\EventServiceProvider中注册App\Events\AccessTokenCreated、App\Events\AccessTokenRefreshed事件。
    2、实现两个事件类，用来创建token及刷新token时,撤销数据库中的其它访问令牌及旧token短暂有效

#### Middleware

* App\Http\Middleware\HashidsEncode


    使用Hashids\Hashids对返回的值进行ids加密，中间件别名为hash-encode，具体使用如下：
 
    ```
        'hash-encode:client_id,prefix'
    ```

    client_id为response中需要加密的key，默认为id；prefix为加密后字符串统一的前缀,默认为空

* App\Http\Middleware\HashidsDecode

    使用Hashids\Hashids对输入的值进行ids解密,中间件别名为hash-decode,具体使用如下：

    ```
        'hash-decode:client_id,prefix'
    ```

    client_id为request中需要解密的key，默认为id；prefix为密文中的前缀，默认为空

* App\Http\Middleware\RequestLogin

    使用tymon\jwt-auth对登录token进行登录校验,别名check-login，具体使用如下:
    
    ```
        'check-login:api'
    ```

    api为认证使用的guard，默认为空，将使用config\auth.php配置的默认guard

* App\Http\Middleware\ApiResponseHandle

    api接口返回处理中间件，别名apiresponse，已经加入到api中间件组中.

## 部署

1、git clone https://github.com/waljqiang/laravel.git

2、move ".env.example" to ".env"并做相应配置

3、配置nginx

```
    前后端分离
    server{
        rewrite ^/cloudnetlot$ /cloudnetlot/frontend/vue/ permanent;
        location /cloudnetlot {
            rewrite /cloudnetlot/(.*)$ /$1 break;
            proxy_pass http://192.168.33.10:8062/#/;

            #proxy settings
            proxy_redirect off;
            proxy_set_header Host $proxy_host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_next_upstream error timeout invalid_header http_500 http_502 http_503 http_504;
            proxy_max_temp_file_size 0;
            proxy_connect_timeout 90;
            proxy_send_timeout 90;
            proxy_read_timeout 90;
            proxy_buffer_size 4k;
            proxy_buffers 4 32k;
            proxy_busy_buffers_size 64k;
            proxy_temp_file_write_size 64k;
        }
    }

    server {
        listen 8062;
        server_name 192.168.33.10;

        root /vagrant/cloudnetlot;
        index index.php index.html index.htm;

        charset utf-8;

        location / {
        }

        location /backend {
            rewrite /backend/(.+)$ /$1 break;
            proxy_pass http://192.168.33.10:8162;

            #proxy settings
            proxy_redirect off;
            proxy_set_header Host $proxy_host;
            #proxy_set_header Raw-Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_next_upstream error timeout invalid_header http_500 http_502 http_503 http_504;
            proxy_max_temp_file_size 0;
            proxy_connect_timeout 90;
            proxy_send_timeout 90;
            proxy_read_timeout 90;
            proxy_buffer_size 4k;
            proxy_buffers 4 32k;
            proxy_busy_buffers_size 64k;
            proxy_temp_file_write_size 64k;
        }
        #location ~.*\.(gif|gp|gpeg|png|bmp|ico|swf|js|css)$ {
        #                root /vagrant/Test;
        #}

        location ~ \.php$ {
                #fastcgi_pass 192.168.33.10:9000;
                fastcgi_pass   unix:/var/run/php5-fpm.sock;
                fastcgi_index /index.php;

                include fastcgi_params;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_param PATH_INFO $fastcgi_path_info;
                fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        }

        location ~ /\.ht{
            deny all;
        }

  }

  server {
        listen 8162;
        server_name 192.168.33.10;

        root /vagrant/cloudnetlot/backend/public;
        index index.php index.html index.htm;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~.*\.(gif|gp|gpeg|png|bmp|ico|swf|js|css)$ {
            root /vagrant;
        }

        location ~ \.php$ {
                fastcgi_pass   unix:/var/run/php5-fpm.sock;
                fastcgi_index index.php;
                fastcgi_split_path_info ^(.+\.php)(/.*)$;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                fastcgi_param PATH_INFO $fastcgi_path_info;
                include /etc/nginx/fastcgi.conf;
      }
  }


```

4、导入backend下cloudnetlot20191018.sql到数据库

5、进入backend目录执行数据迁移

```
    php artisan migrate
```

6、执行passport秘钥生成

```
    php artisan passport:install
```

7、执行认证服务users表数据填充

```
    php artisan db:seeder
```

* 客户端生成

```
    php artisan open:client {$account} --clientname='123'
```

## 自建

* 搭建服务器环境

    1、统一由docker部署，服务器环境有docker镜像运行。镜像包目录说明:

    ```
    --cloudnetlot
        --script
        --soft
        --www
        --cloudnetlotdata
        --cloudnetlotdaemon
        --cloudnetlotserver
        --cloudnetlotvsftpd
        --cloudnetlotencode
    ```


    | 目录| 描述|
    |:---:|:---:|
    |script|相关脚本文件|
    |soft|环境所需镜像及相关软件|
    |www|代码目录|
    |cloudnetlotdata|数据层环境相关|
    |cloudnetlotdaemon|服务层环境相关|
    |cloudnetlotserver|应用层环境相关|
    |cloudnetlotvsftpd|ftp服务相关|
    |cloudnetlotencode|代码混淆服务环境相关|

    2、部署步骤说明：

    1)将压缩包上传到用户服务器(/usr/local目录为例,用户服务器地址为192.168.111.100)并解压到当前目录

    2)进入到/usr/local/cloudnetlot/script目录,并执行下面命令

    ```
        ./install.sh [服务器IP] [服务名] [configmap]
    ```
    服务器IP为用户服务器地址。

    服务名可以写多个，中间用空格隔开，脚本只会部署此处填写的服务，具体服务名与服务器对应关系请参阅服务容器与服务名对应关系一节。

    configmap为固定字符串，如果有此字符串，则会将各容器中各应用的配置文件映射到宿主机中，否则，不会将容器中各应用配置文件映射到宿主机。

     此脚本命令必须要两个参数，第一个参数固定为用户服务器IP地址，第二个参数当仅两个参数时必须为服务容器名，当参数个数大于两个后，从第二个参数开始依次为服务容器名或configmap，configmap如果存在，则在启动容器时会将各容器中服务的配置文件映射到宿主机的对应目录.系统只会搭建命令中指定的服务容器。

    示例：
    ```
        ./install.sh 192.168.111.100 cloudnetlotdata cloudnetlotvsftpd cloudnetlotdaemon cloudnetlotserver yucloudnetlotencode configmap
    ```
    3、服务器环境说明

    ```
    cloudnetlotdata
        mysql5.7.22(root/admin@123)
        redis4.0.8(1f494c4e0df9b837dbcc82eebed35ca3f2ed3fc5f6428d75bb542583fda2170f)
        emqttd2.3.11(admin/public)
    cloudnetlotdaemon
        php7.1.20
        swoole4.1.0 
        hiredis0.13.3 
        phpredis3.1.6
        phpinotify2.0.0
    cloudnetlotvsftpd
        vsftpd3.0(ftpuser/123456)
    cloudnetlotserver
        nginx1.6.2
        php5.6.38 
    cloudnetlotencode
        php5.6.38
        php7.1.20
        php-beast-master
    ```


* license由线上环境后台生成，license放到自建代码storage/app/public中，部署到用户服务器中。

* 将代码放于服务器的/usr/local/cloudnetlot/www/cloudnetlot下，用户群组为www-data，storage目录改为777,

* 执行数据迁移

    docker exec -it cloudnetlotserver /bin/bash -c 'cd /usr/local/www/cloudnetlot/backend && php artisan migrate'