

（读配置文件）          （抓取解析html/xml）        （从缓存中取或者请求抓取解析html）
获取首页列表 －－－－－－－> 获取摘要列表 －－－－－－－> 获取正文内容
配置可修改                解析规则可修改扩展          正文解析规则可修改扩展

前端发起3次请求（并缓存结果），后台采用aop，以支持扩展
// goaop测试demo
http://localhost/t-goaop/demos/?showcase=human-advices

// 安装goaop
composer require goaop/framework

