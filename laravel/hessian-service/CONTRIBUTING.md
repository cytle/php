## CONTRIBUTING

我们可以在`vendor`目录下直接修改, 这样可以很快的增加一些错误信息、枚举、立即要用的辅助类、函数等等，本地开发环境就即时生效了。

改完用`git`提交到一个分支，在发布`qa`环境前合并到`master`就行，本地的变动可以在`composer update`时覆盖掉，或者切回`master`再`update`。


### 添加一个新的Service

```shell
$ php artisan service:make <apiName> <apiPath>
```

```
Arguments:
  apiName               api 名字，如OrderService 就为 order
  apiPath               api path
```

执行后将在`/vendor/store59/hessian-service/src/Services`下新增service
