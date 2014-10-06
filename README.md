# 汉字转拼音

# 安装
添加 `"lixunguan/pinyin": "*"` 到 [`composer.json`](http://getcomposer.org).

```
composer.phar install

```

# 获取拼音首字母

```php
  return Pinyin::make('我们')->firstLetter(); // 输出 wm
```

# 获取完整拼音

```php
  return Pinyin::make('我们')->full(); // 输出 women
```
