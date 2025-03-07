# 智简魔方业务管理系统v10(ZJMF-CBAP v10)：您一直在寻找的 业务管理软件。http://www.idcsmart.com

ZJMF-CBAP v10是开源的业务管理系统，基于PHP+MYSQL开发的一套小型易于部署的业务管理核心，具有极强的扩展能力，非常方便的安装方式，用户可在5分钟内部署属于自己的业务管理系统，ZJMF-CBAP v10内置丰富的应用商店，可根据您的业务需求，安装自己需要的应用，极低的上手成本。
您也可以适用ZJMF-CBAP v10作为您的业务开发核心底层，开发属于您自己的业务系统并再次对外发布。

## 关键能力
### 插件能力
具备简单易开发的插件开发能力，您可快速构建您需要的插件功能。
### 应用商店
内置丰富的应用商店，用户可一键安装应用，快速构建自己喜欢的业务系统能力，开发者也可免费提交应用至商店赚取收益。依托智简魔方在行业官方的知名度和强大的用户支撑，建立良好的应用生态。
### 丰富的行业模块 
无论是您将系统用于哪个产品，抑或是哪个行业，通过独特设计的订购、管理、开通模块分离，都可实现任意产品的强定制管理，无论您是私有云（nokvm、zstack、zkeys、魔方云、solusvm、pve、vmware）还是公有云（阿里云、华为云、腾讯云）、或者是公有云代理商模式，我们都可强力无缝支撑。
哪怕您用于其他行业，我们也有所准备，面向汽车销售的在线车辆选配模块，车辆管理模块。面向虚拟运营商的号码模块，流量模块。
### 短信/邮件/主题
内置短信扩展接口，邮件扩展接口，主题扩展能力，开发者可快速开发短信邮件与主题，主题使用前后端分离模式，您只需要调用API即可，无论您使用jq或VUE，哪怕是react也不在话下。
### SaaS服务提供
系统内置了常用接口，如短信，邮件，并且智简魔方提供了SaaS服务，您无需额外购买或者配置短信邮件，实名接口，即可使用。让您的业务上线更加便捷高效。
### HOOK能力
系统具有丰富的hook模块，类似whmcs的hook，您可在系统任何动作时增加您的操作，方便您进行二次开发



***

## 如何安装（开发者预览版）
### 注意：
当前版本为开发者预览版，不支持普通用户安装，目前没有会员中心界面，普通用户安装后无法使用！！！<br>
开发者可使用当前版本，进行主题，插件开发。<br>
### 安装步骤
运行环境要求：PHP7.2或以上  Mysql5.6或5.7<br>
所有代码传到网站根目录<br>
导入根目录的rc.sql文件<br>
### 伪静态设置
apache无需设置，public下已经存在.htaccess伪静态文件<br>
nginx用户，请将public/nginx.conf设置到您的nginx配置中<br>
### 配置数据库
修改数据库配置文件config.php<br>
访问后台 http://ip/admin<br>
用户名admin<br>
密码123456<br>


### 安装后API文档访问路径
http://ip/doc




***

## 如何开发
### 支付接口开发文档
https://www.idcsmart.com/wiki_list/882.html
### 插件开发文档
https://www.idcsmart.com/wiki_list/883.html
### 主题开发文档
https://www.idcsmart.com/wiki_list/884.html
### 短信接口开发文档
https://www.idcsmart.com/wiki_list/885.html
### 邮件接口开发文档
https://www.idcsmart.com/wiki_list/886.html
### HOOK

***

## License
Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at<br>
http://www.apache.org/licenses/LICENSE-2.0<br>
除非适用法律要求或书面同意，否则根据许可分发的软件将按“原样”分发，没有任何明示或暗示的保证或条件。有关许可下的特定语言管理权限和限制，请参阅许可。
