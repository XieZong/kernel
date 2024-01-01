## 安装方式1

- 修改`composer.json`

```
"repositories": [
    {
        "type": "path",
        "url": "./kernel"
    }
]
```

- 安装

```
composer require xiezong/kernel dev-master
```

- 引入服务提供者

```
$app->register(Kernel\Providers\KernelServiceProvider::class);
```

## 安装方式2

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