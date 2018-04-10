<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->assign("title","标题");
        return $this->display();
    }

    public function test() {
        //C方法,读取配置
        C("name","yaoMing");//设置值
        $name = C("name");//获取值
        $name1 = C("name1");//获取配置文件的值
//        echo "<pre>";
//        print_r($name);//打印值
//        echo "<hr>";
//        print_r($name1);//打印值
//        echo "</pre>";

        //M方法，操作数据库，没有模型文件
//        $m = M('student');
//        $r = $m->select();
//        echo "<pre>";
//        print_r($r);
//        echo "</pre>";

        //D方法，操作模型文件
//        $d = D('Index');
//        $r = $d->select();
//        echo "<pre>";
//        print_r($r);
//        echo "</pre>";

        //E方法，抛出异常
//        E("这是异常信息!");

        //session的用法
        //1.访问：http://localhost/thinkphp32/index.php?c=Index&a=test
        //2.访问：http://localhost/thinkphp32/index.php/Home/Index/test
        $arr = array("name"=>"yaoMing");
        session("name",$arr);
        echo "<pre>";
        print_r($_SESSION['name']);
        echo "</pre>";
    }


}