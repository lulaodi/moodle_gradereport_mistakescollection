# moodle_gradereport_mistakescollection

#### 介绍
Moodle平台错题集功能，基于Moodle3.10.1版本

#### 作者 陆老弟（Jason）

- QQ：1129332567
- 邮箱：1129332567@qq.com
- Moodle平台技术服务淘宝店：https://shop362395590.taobao.com
- Moodle交流QQ群：897327167
- Moodle平台体验：http://47.114.124.77/




#### 效果预览

1.错题列表页(学生端):

![错题列表](https://images.gitee.com/uploads/images/2021/0312/162820_9c33801b_8792465.png "屏幕截图.png")


2.错题列表页(老师端，查看所有用户错题):

![错题列表](https://images.gitee.com/uploads/images/2021/0312/162938_0ad22628_8792465.png "屏幕截图.png")


3.题目回顾页:


![题目回顾](https://images.gitee.com/uploads/images/2021/0312/163007_45af836f_8792465.png "屏幕截图.png")


4.学生端错题列表是否显示正确答案设置页:

![显示正确答案](https://images.gitee.com/uploads/images/2021/0316/170440_1b9d08db_8792465.png "屏幕截图.png")


5.教师端分隔小组筛选功能:

![分隔小组筛选](https://images.gitee.com/uploads/images/2021/0327/191453_cb149ba5_8792465.png "屏幕截图.png")


#### 使用说明

- 安装方法：

 **方法1：** 本错题集已按Moodle规范开发成一个Moodle插件,只需用超级管理员账号登录，在网站管理》插件》安装插件上传安装即可，方便简单。（推荐）

 **方法2：** 将本项目解压放到moodle/grade/report目录下，用超级管理员账号登录Moodle平台，Moodle会自动检测安装，按提示完成安装即可。

- 访问位置：进入一门课程，点击成绩，错题集内嵌在这门课程报表中。


#### 免责声明

- 由于发布者技术有限，本插件可能会有未知风险，故没有质量担保，若出现任何问题，无法向发布者追责。若你下载使用了本插件，就意味着默认同意了本条款。

#### 捐赠

- 开发不易，若这插件对你有用，你的些许打赏更能鼓励我持续更新。

- 支付宝扫一扫，向我打赏
![aplipay](https://images.gitee.com/uploads/images/2021/0312/171836_e2a4027a_8792465.png "屏幕截图.png")

- 微信扫一扫，向我打赏
![weixin](https://images.gitee.com/uploads/images/2021/0312/171751_f235f9cb_8792465.png "屏幕截图.png")

#### 鸣谢


#### 更新日志
### - 20210316 v1.1

1. 在插件设置里添加开关控制学生端错题列表页是否显示正确答案
2. 修复部分正确的题目不会在错题列表显示问题

### - 20210318 v1.1

1. 完善题目回顾查看权限验证，限制只有本人或课程管理者才有权限回顾题目
2. 修复题目回顾页不能标记试题bug，及将点击结束回顾跳转链接修改为跳转至错题集页

### - 20210327 v1.1

1. 教师界面添加分隔小组筛选功能。
