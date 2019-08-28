# AcgIce搜索开源说明

AcgIce搜索是使用PHP7开发的一款聚合搜索引擎，为了更好的发展所以开源，协议采用AGPL-3.0，希望您可以遵守这个开源协议。欢迎一起开发和维护Acgice搜索。

网址：https://www.acgice.com/

公司：许昌市冰尘网络科技有限公司



## 环境要求

```
PHP > 7.3
Memcache Or Redis
```

## 环境依赖

- ThinkPHP5.0 http://www.thinkphp.cn/
- QueryList https://github.com/jae-jae/querylist 

```
composer require jaeger/querylist
composer require jaeger/querylist-absolute-url
```

AcgIce 扩展（搜索源）：https://github.com/orzice/acgice-extend

```
composer require acgice/acgice-extend
```

HTTP客户端采用 GuzzleHttp，当你安装了 QueryList 后 默认就安装了。（你要是用Curl也没事。）

Guzzle文档：https://guzzle-cn.readthedocs.io/zh_CN/latest/overview.html



## Acgice搜索反馈渠道和联系方式

```
QQ: 1073519986 

邮件: admin@orzice.com

公司：许昌市冰尘网络科技有限公司
```



由此可以只用写搜索扩展（核心）就好了，获取数据，系统会自动处理进行展现的。