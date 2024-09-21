## 使用安装

### 在线

```sh
composer require xiezong/kernel 
```

- 引入服务提供者

```
$app->register(Kernel\Providers\KernelServiceProvider::class);
```

### 离线

- 修改`composer.json`

```
"autoload": {
    "psr-4": {
        "Kernel\\": "kernel/"
    },
    "files": [
      "kernel/helper.php"
    ]
}
```

- 引入服务提供者

```
$app->register(Kernel\Providers\KernelServiceProvider::class);
```
- 更新composer
```
composer dumpautoload
```

## 开发安装

- 修改`composer.json`

```
"repositories": [
    {
        "type": "path",
        "url": "./kernel"，
        "options": {
            "symlink": true
        }
    }
]
```

- 安装

```sh
#windows
composer require xiezong/kernel dev-master

#linux
composer require xiezong/kernel dev-main
```

- 引入服务提供者

```
$app->register(Kernel\Providers\KernelServiceProvider::class);
```
