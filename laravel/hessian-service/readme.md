# ERP - laravel基础支持 - HessianService59

解决hessian调用，让调用Service变成调用静态方法


## 快速开始

### 1. 添加依赖

```shell
$ composer require store59/serivce
```

### 2. 添加HessianServices服务提供者和Facade

```php
'providers' => array(
    // ...
    HessianService59\Providers\HessianServiceServiceProvider::class,
)

'aliases' => array(
    // ...
    'HessianServices' => HessianService59\Providers\HessianServiceFacade::class,
)
```

### 3. 添加配置

```shell
$ php artisan service:config-publish
```
执行后将生成`config/hessian_services.php`文件


### 4. 添加异常处理
```php
// app/Exptions/Handler
class Handler extends ExceptionHandler
{
    // ...

    public function render($request, Exception $e)
    {
        // ...
        $response = app('hessianService')->renderException($request, $e);
        if (! $response instanceof Exception) {
            return $response;
        }
```

也可以自己处理异常,会抛出以下两个异常

    1) HessianService59\Exceptions\HessianException;
    2) HessianService59\Exceptions\ServiceApiResultException;

### 5. 调用

```php
// use HessianService59\Services\UserService\User;
$uid = 1412057569;
$user = User::getUser($uid); // 返回的是data里的数据
```


## Document

[laravel基础项目 V1.0 - services59](http://doc.oschina.net/laravel.infrastructure?t=92281)


## 项目结构
```
.
├── composer.json
├── phpunit.xml
├── readme.md
├── src
│   ├── Configs                 // hessian相关配置
│   ├── Contracts               // 接口
│   ├── Exceptions              // 异常类
│   ├── Helpers                 // 辅助方法
│   ├── Libraries               // 类库
│   ├── Parsers                 // 对象转化规则
│   ├── Services                // Service定义
│   └── ServiceBuilder.php      // 请求Service的方法封装
└── tests              // 测试（粗略测试）
    ├── Helpers
    └── TestCase.php
```


## 如何贡献

[CONTRIBUTING](http://code.59store.com/erp/hessian-service/blob/master/CONTRIBUTING.md)
