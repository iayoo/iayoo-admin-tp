Iayoo Admin For ThinkPHP
===============

> 运行环境要求PHP7.1+，兼容PHP8.0。'
>
> 基于 `thinkphp 6.0` 

## 安装

### Nginx

在 `location /` 下配置下示内容

```
location / {
    # 新增内容开始
    if (!-e $request_filename) {
        rewrite  ^(.*)$  /index.php?s=/$1  last;
        break;
    }
    # 新增内容结束
}
```