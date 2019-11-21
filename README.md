# Any
最简化权限管理系统，基于 Laravel5.5 开发。

## 安装 Any
下载本项目代码到本地：
```
git clone https://github.com/FlyingOranges/any.git
```

进入到项目然后 `composer` 安装：

```
cd any

composer install
```

配置 `.env` 文件：
```
[sudo]cp .env.example .env

php artisan key:generate
```

> Linux 和 Mac 下注意执行权限 !

配置数据库：
```
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

迁移数据：
```
php artisan migrate --seed
```

OK,项目已经配置完成，直接访问首页然后登录即可，不清楚路由的可以直接去看 `routes/admin.php` 文件。默认管理员账号：`admin` , 密码：`123456` 。

## 转载说明
`any` 项目，我是在[https://github.com/lanceWan/any.git](https://github.com/lanceWan/any.git)处转载,仅仅作为学习使用。