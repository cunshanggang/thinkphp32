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
        $m = "欢迎您关注唯有读书，1000谷粒已经到账，可以点击<a href='http://book.3g.cn/wys/index.php?m=Home&c=Index&a=index&jump=1'>这里</a>或下方菜单栏任性看书了！\r\n推荐阅读一下书本:\r\n天后养成：征服凶猛BOSS<a href='http://book.3g.cn/wys/index.php?m=Home&c=Book&a=content&bookid=464440&chapterid=43&wysjump=1'>【点击阅读】</a>\r\n宠妻入骨：冷酷总裁约不约<a href='http://book.3g.cn/wys/index.php?m=Home&c=Book&a=content&bookid=477922&chapterid=28&wysjump=1'>【点击阅读】</a>\r\n无限婚契，枕上总裁欢乐多<a href='http://book.3g.cn/wys/index.php?m=Home&c=Book&a=content&bookid=481094&chapterid=47&wysjump=1'>【点击阅读】</a>";
        echo $m;
    }

    public function search() {

        $this->display();
    }

    public function searchData() {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
//        exit($_POST['username']);
//        exit;
//        return $_REQUEST['username'];
    }

    public function getData() {
        $arr = array(
            '0'=>array('小明',"2018-03-26 上午 06:43:07"),
            '1'=>array('小明',"2018-03-26 上午 07:43:07"),
            '2'=>array('小明',"2018-03-27 下午 08:43:07"),
            '3'=>array('小明',"2018-03-27 下午 06:43:07")
        );

        $data = array();
        foreach ($arr as $k1=>$v1) {
            $d = array();
            //将同一天的打包到同一个数组
            $name = $v1[0];
//            echo $name;echo "<hr>";
            $time = mb_substr($v1[1],0,10);
//            echo $time;
//            echo "<hr>";
            $strTime = strtotime(mb_substr($v1[1],14,9));
//            echo mb_substr($v1[1],14,9);
//            echo "<hr>";
//            echo $strTime;

            foreach($arr as $k2=>$v2) {
                $name1 = $v2[0];
//                echo $name1;
//                echo "<hr>";
                $time1 = mb_substr($v2[1],0,10);
//                echo "<hr>";
//                echo $time1;
                $strTime1 = strtotime(mb_substr($v2[1],14,9));
//                echo "<hr>";
//                echo $strTime1;
//                echo "<hr>";
//                echo "<hr>";
                if(($name == $name1) && ($time == $time1) && ($strTime1>$strTime)) {
                    if(strstr($v2[1],"上午")) {
                        $d['morning'] = strtotime(str_replace(" 上午 "," ",$v2[1]));
                        $d['name'] = $v2[0];
                    }

                    if(strstr($v2[1],"下午")) {
                        $d['afternoon'] = strtotime(str_replace(" 下午 "," ",$v2[1]))+12*60*60;
//                        echo $d['afternoon'];
//                        echo "<hr>";
                        $d['name'] = $v2[0];
                    }

                }else{
                    $d['name'] = $name;
                    $d['morning'] = strtotime(str_replace(" 上午 "," ",$v1[1]));
//                    echo strtotime(str_replace(" 下午 "," ",$v1[1]));
//                    echo "<hr>";
                    $d['afternoon'] = strtotime(str_replace(" 下午 "," ",$v1[1]))+12*60*60;
//                    echo "<hr>";
//                    echo $d['afternoon'];
                 }

//                if(($name == $name1) && ($time == $time1) && strstr($v2[1],"下午") && ($strTime1>$strTime)) {
//                    $d['afternoon'] = strtotime(str_replace(" 下午 "," ",$v2[1]))+12*60*60;
//                    $d['name'] = $v2[0];
//                }


            }
            if(!empty($d['morning']) && !empty($d['afternoon']) && !empty($d['name'])) {
                array_push($data,$d);
            }

        }
        echo "<pre>";
//        print_r(array_unique($data));
        print_r($data);
        echo "</pre>";
//        echo "<pre>";
//        print_r($arr);
//        echo "</pre>";
        foreach($arr as $k=>$v) {
            if(strstr($v['1'],'上午')) {
                $arr[$k]['1'] = strtotime(str_replace(" 上午 "," ",$v['1']));//上午的时间
            }

            if(strstr($v['1'],'下午')) {
                $arr[$k]['1'] = strtotime(str_replace(" 下午 "," ",$v['1']))+12*60*60;//下午的时间要添加多12个小时
            }
        }

//        echo "<pre>";
//        print_r($arr);
//        echo "</pre>";

//        echo strtotime("06:43:07");
//        echo "<hr>";
//        echo strtotime("07:43:07");
    }

    public function strTime() {
        $v = "2018-03-26 下午 06:43:07";
        $length =  mb_substr($v,0,13);
//        echo strlen($length);
//        $strTime = mb_substr($v,13,9);
//        echo $strTime;
        $arr = array(
            '0'=>array('小明',"2018-03-26 上午 06:43:07"),
            '1'=>array('小明',"2018-03-26 上午 08:43:07"),
            '2'=>array('小明',"2018-03-26 下午 07:43:07"),
            '3'=>array('小明',"2018-03-26 下午 06:43:07"),
        );

        $data = array();

            $d = array();
            //将同一天的打包到同一个数组
            $name = $arr[0][0];
//            echo $name;echo "<hr>";
            $time = mb_substr($arr[0][1],0,10);
//        echo $time;echo "<hr>";
//        echo mb_substr($arr[0][1],14,9);
            $strTime = strtotime(mb_substr($arr[0][1],14,9));
//        echo $strTime;echo "<hr>";
                $name1 = $arr[1][0];
//            echo $name1;
                $time1 = mb_substr($arr[1][1],0,10);
//        echo $time1;
                $strTime1 = strtotime(mb_substr($arr[1][1],14,9));
//        echo $strTime1;echo "<hr>";
//        print_r($strTime1>$strTime);
        echo "<pre>";
//        echo $arr[1][1];
//        print_r(strstr($arr[1][1],"上午"));
//        echo "</pre>";
                if(($name == $name1) && ($time == $time1) && strstr($arr[1][1],"上午") && ($strTime1>$strTime)) {
                    $d['morning'] = strtotime(str_replace(" 上午 "," ",$arr[1][1]));
                }
                if(($name == $name1) && ($time == $time1) && strstr($arr[1][1],"下午") && ($strTime1>$strTime)) {
                    $d['afternoon'] = strtotime(str_replace(" 下午 "," ",$arr[1][1]))+12*60*60;
                }
                $d['name'] =$arr[1][0];

            array_push($data,$d);

        echo "<pre>";
        print_r($data);
        echo "</pre>";


    }

    public function assembleData() {
        $arr = array(
            '0'=>array('小明',"2018-03-26 上午 06:43:07"),
            '1'=>array('小明',"2018-03-26 上午 08:43:07"),
            '2'=>array('小明',"2018-03-26 下午 07:43:07"),
            '3'=>array('小明',"2018-03-26 下午 06:43:07")
        );

        //将后面的时间转换为时间戳
        foreach ($arr as $k=>$v) {
            if(strstr($v[1],"上午")) {
                $arr[$k][1] = strtotime(str_replace(" 上午 "," ",$v[1]));
                //区分上午,0:表示上午
                $arr[$k][2] = 0;
            }
            if(strstr($v[1],"下午")) {
                $arr[$k][1] = strtotime(str_replace(" 下午 "," ",$v[1]))+60*60*12;
                //区分下午,1:表示下午
                $arr[$k][2] = 1;
            }
        }

        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        $morning = array();//早上的时间
        $afternoon = array();
        foreach($arr as $k1=>$v1) {
            if($v1[2] == 0) {
                $morning[$k1][0] = $v1[0];
                $morning[$k1][1] = $v1[1];
                $morning[$k1][2] = $v1[2];
            }else{
                $afternoon[$k1][0] = $v1[0];
                $afternoon[$k1][1] = $v1[1];
                $afternoon[$k1][2] = $v1[2];
            }
        }

        echo "<pre>";
        print_r(array_values($morning));
        echo "<hr>";
        print_r(array_values($afternoon));
        echo "</pre>";



    }


}