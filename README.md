<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-advanced.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-advanced)

DIRECTORY STRUCTURE
-------------------
**day1**
1.主要功能模块
开发环境	Window
开发工具	Phpstorm+PHP7.0+GIT+Apache
相关技术	Yii2.0+CDN+jQuery+sphinx
**品牌管理**
系统后台设计：后台admin.shop.com 对url地址美化
*1.品牌概念模块*
 *1.1.需求*
   品牌管理功能涉及品牌的列表展示、品牌添加、修改、删除功能。
   品牌需要保存缩略图和简介。
   品牌删除使用逻辑删除。
 *1.2.要点及难点*
   1)删除使用逻辑删除,只改变status属性,不删除记录
   2)使用webuploader插件,提升用户体验
   3)使用composer下载和安装webuploader
   
   
   
   
   
**day2**
*1.文章管理模块*
 1.1.需求
     1)文章的增删改
     2)文章分类的增删改
 1.2.设计要点、难点及解决方案
     1)文章模型和文章详情模型建立一对一的关系
     2)富文本框添加内容
     3)文章分类不能重复,通过添加验证规则unique解决
     4)文章垂直分表,创建表单使用文章模型和文章详情模型
 


**day3**
*1.商品分类模块*
1.1需求
1.商品分类的增删改查
2.商品分类支持无限级分类
3.方便直观的显示分类层级
4.分类层级允许修改

*2.设计要点*
    1)使用了嵌套集合模型
    2）使用Ztree插件显示分类层级
    3)设计数据表
*3.流程*
   1）安装nested set插件
   2）配置NestedSetBehavior
   3)下载ztree插件 
   https://packagist.org/packages/liyuze/yii2-ztree
   composer require liyuze/yii2-ztree 
   4）下载treegrid插件 实现首页列表展示
   https://packagist.org/packages/leandrogehlen/yii2-treegrid
   composer require leandrogehlen/yii2-treegrid
*4.难点*
  1）nestedset行为的配置和使用
  2）ztree插件的使用
  3）treegrid插件的使用，首页删改，插件的操作不怎么会
  