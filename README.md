##读我
使用瀑布流展示图片.

其中使用phpquery以及自己写的一个lib放在application/libraries里面. 是利用cookie登陆访问页面.

获取cookie使用firefox下firebug直接下载. 

填写表单获取微博地址. 从微博内容页获取头像以及昵称.

头像使用  http://tp{uid % 4}.sianimg.cn/{uid}/{180|50}/{rand()}/{1|0} 来生成. 
昵称获取的是 内容页js的配置  使用正则匹配 .

## License

MIT with [CodeIgniter Amendments](http://codeigniter.com/user_guide/license.html)
