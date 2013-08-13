<?php 
class SignIn{
  var $cookie_file; // 设置Cookie文件保存路径及文件名 
  var $loginurl;//登陆地地址
  var $actionstr;//登陆参数
  function __construct()
  {
    //$this->cookie_file=tempnam("./TEMP","cookies1.txt"); 
    $this->cookie_file = dirname(__FILE__).'/weibo.txt';
  }

  function vlogin()
  {
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL,$this->loginurl);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//是否显示数据 0为显示 1为不显示
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $this->actionstr);
     curl_setopt($ch,CURLOPT_COOKIEJAR,$this->cookie_file);
     $data = curl_exec($ch);
     curl_close($ch);
  }

  function gethtml($url)
  {
    
      $curl = curl_init(); // 启动一个CURL会话    
      curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查    
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在    
      curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0'); // 模拟用户使用的浏览器    
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转    
      curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer    
      curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求    
      curl_setopt($curl, CURLOPT_COOKIEFILE, "$this->cookie_file"); // 读取上面所储存的Cookie信息    
      curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环    
      curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容    
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回    
      $tmpInfo = curl_exec($curl); // 执行操作    
      if (curl_errno($curl)) {    
      echo 'Errno'.curl_error($curl);    
      }    
      curl_close($curl); // 关闭CURL会话    
      return $tmpInfo; // 返回数据
     
  }
}