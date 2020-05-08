# 海马汇的ts打卡 微信公众号版

> 由于打卡机制的盛行，和更加严苛，ts只能当天填写，漏掉就会扣工资。弄得我怕怕，于是搞了一个兜底逻辑，每天跑一次，防止漏填。

### 获取token 目前还不完善，应该是抓包有遗漏

> 提示 缺失认证

### 变相方案

> 首先，还是得抓包，抓pc微信的公众号的就行，需要 access_token。注意了，获取后就别再用公众号的打卡功能了，（用海马汇app不影响，是两个不同的域名和接口体系）。

> 然后你会惊讶的发现，只要不覆盖（重新获取access_token)，它会一直有效，只需要改变打卡接口中的日期参数就能正常run了，真香。

### 最后

> 我用的fiddler没法搞定token获取接口了，I need your help!欢迎大家分享方案。为了不让我们本来就够低的工资再被克扣。
> access_token获取接口，需要一个code参数，在微信中运行：https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc81d7e20fa7b9564&redirect_uri=http://mobile-app.hand-china.com/hrmsstatic/hrms/hrms_wx_ts/indexWorkflow.html&response_type=code&scope=snsapi_userinfo&state=notice&connect_redirect=1#wechat_redirect 。可以在微信中打开上述地址获取code，然后用我sdk中的方法获取access_token。headers里面的token算法很简单，我找出来了，不需要引入了。

### 最后的最后。
+ 我放了一个我自用的脚本上来，own_demo.php。有兴趣的可以参考一下，带一个简易的qq打卡成功通知。
+ 最后，祝汉德的大家好运！
