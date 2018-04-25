<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    protected $nba_playoff = array("rockets"=>"火箭","wolves"=>"狼","thunder"=>"雷霆","jazz"=>"爵士","trailblazers"=>"开拓者","pelicans"=>"鹈鹕","warriors"=>"勇士","spurs"=>"马刺","raptors"=>"猛龙","wizards"=>"奇才","cavaliers"=>"骑士","pacers"=>"步行者","76ers"=>"76人","heat"=>"热火","celtics"=>"凯尔特人","bucks"=>"雄鹿");
    public function index(){
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');

        $this->assign("title","标题");
        return $this->display();
    }

    //获取提交数据
    public function poster() {
        if(IS_POST) {
            $data['nickname'] = $_REQUEST['nickname'];
            $data['nba_team'] = $_REQUEST['nba_team'];
            $data['idiom']    = $_REQUEST['idiom'];
            $data['wish']     = $_REQUEST['wish'];
//            $data['add_time'] = time();
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
            $nba_fans = D("NbaFans");
//            echo "<pre>";
//            print_r($nba_fans->create($data));
//            echo "</pre>";
            if(!$nba_fans->create()) {
//                exit($nba_fans->getError());
                $this->error($nba_fans->getError());
            }else{
                $nba_fans->add();
                $data['nba_team'] = $this->team($data['nba_team']);
//                echo "<pre>";
//                print_r($data);
//                echo "</pre>";exti;
                $this->assign('result',$data);
                $this->display();
            }
        }
    }

    //海报展示
    public function poster1(){
        $str = "休斯顿火箭队";
//        echo "<pre>";
//        print_r(strstr($str,"勇士"));
//        echo "</pre>";
        foreach ($this->nba_playoff as $k=>$v) {
            $reg = '/('.$v.')/';
            preg_match($reg,$str,$mat);


        }


        $this->display();
    }

    //获取NBA球队的英文名称用来变换与之对应的背景图片
    public function team($nba_team) {
        foreach ($this->nba_playoff as $k=>$v) {
            $reg = '/('.$v.')/';
            preg_match($reg,$nba_team,$mat);
            //若无法匹配用户输入的球队，默认为：火箭队
            if(!empty($mat[1])) {
                if($mat[1] == $v) {
                    $team[$k] = $v;
                }
            }
        }
        //若没有匹配的球队，默认是火箭队
        if(empty($team)) {
            $team['rockets'] = "火箭";
        }
        //返回的是索引值，如：rockets=>火箭,返回：0=>rockets
        $result = array_keys($team);
        return $result[0];
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
//        $arr = array("name"=>"yaoMing");
//        session("name",$arr);
//        echo "<pre>";
//        print_r($_SESSION['name']);
//        echo "</pre>";
    }
}