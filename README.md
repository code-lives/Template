# 微信发送小程序模版消息

[官方接口文档](https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#6)

## 安装
composer require code-lives/template


## 发送模版消息
```php

$config=[
    'appid'=> '',
    'secret'=> '',
];
//获取token
 $token = \Applet\Template\Wxtemplate::getInstance()->config($config)->getToken();
 //发送
 $data=\Applet\Template\Wxtemplate::getInstance()->config($config)->send($parm, $token['access_token']);

```



