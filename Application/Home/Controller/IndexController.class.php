<?php
namespace Home\Controller;
use Think\Controller;
Vendor('log4php.Logger');
class IndexController extends Controller {
    protected $nba_playoff = array("rockets"=>"火箭","wolves"=>"狼","thunder"=>"雷霆","jazz"=>"爵士","trailblazers"=>"开拓者","pelicans"=>"鹈鹕","warriors"=>"勇士","spurs"=>"马刺","raptors"=>"猛龙","wizards"=>"奇才","cavaliers"=>"骑士","pacers"=>"步行者","76ers"=>"76人","heat"=>"热火","celtics"=>"凯尔特人","bucks"=>"雄鹿");

    public function _initialize() {
        //是谁告诉你一定要加 \ ？？？？ 只有在完全限定名称的情况下才需要加 \
        //如果不加， 将会在当前命名空间下寻找这个类
        \Logger::configure(CONF_PATH . 'log4php.xml');//命名空间，加\表示全局，不加表示当前。
        $logger = \Logger::getLogger("accesslog");
        $logger->info('1');
        //写入日志
        $logger->info("info日志内容");
        $logger->error("error日志内容");
        $logger->debug("debug日志内容");
    }
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

    public function batchInserts() {
        $m = M('student',"",'mysql://root:@localhost/tp32#utf8');
        $data[0] = array('name'=>'林一鸣','gender'=>'','age'=>24,'class'=>'1201','major'=>'计算机科学与技术');
        $data[1] = array('name'=>'林书桓','gender'=>'','age'=>23,'class'=>'1202','major'=>'计算机科学与技术');
        $data[2] = array('name'=>'','gender'=>'','age'=>'','class'=>'','major'=>'');
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";exit;
        $r = $m->addAll($data);
        echo "<pre>";
        print_r($r);
        echo "</pre>";
    }

    public function bdbook() {
        $m = M('book_ranking',"",'mysql://root:@localhost/tp32#utf8');
//        echo "<pre>";
//        print_r($m);
        $r = $m->select();
//        $r1 = $m->getLastSql();
//        echo $r1;
//        echo "<pre>";
//        print_r($r);
//        echo "</pre>";exit;

//        $word = "下一站天后";
//        $url = "http://www.baidu.com/s?wd=".$word;
//        // 构造包头，模拟浏览器请求
//        $header = array (
//            "Host:www.baidu.com",
//            "Content-Type:application/x-www-form-urlencoded",//post请求
//            "Connection: keep-alive",
//            'Referer:http://www.baidu.com',
//            'User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; BIDUBrowser 2.6)'
//        );
//        $ch = curl_init ();
//        curl_setopt ( $ch, CURLOPT_URL, $url );
//        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
//        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
//        $content = curl_exec ( $ch );
//        if ($content == FALSE) {
//            echo "error:" . curl_error ( $ch );
//        }
//        curl_close ( $ch );
//
/*        $reg = '/<div class="result c-container " id="1"[\s\S]*?><em>'."$word".'<\/em>/';*/
//        preg_match_all($reg,$content,$match);
//        echo "<pre>";
//        print_r($match);
//        echo "</pre>";
//        $r = array('0'=>array('id'=>'289176','book_name'=>'下一站天后'));
        foreach($r as $k=>$v) {
            $re = $this->getcurl($v['book_name']);
//            echo "<pre>";
//            print_r($re);
//            echo "</pre>";
            if(empty($re[0])) {
               $book_name = $v['book_name'];
               $data['is_top'] = "否";
//               echo "<pre>";
//               print_r($m);
//               echo "</pre>";
               $res = $m->where("book_name='$book_name'")->save($data);
//               echo "<hr>";
//               echo $m->getLastSql();
//               echo "<hr>";
               echo $res;
            }
        }
//        $data['is_top'] = 0;
//        $res = $m->where("book_name='下一站天后'")->save($data);
//        $r = $m->getLastSql();
//        dump($r);
//        dump($m->_sql());
//        echo "<hr>";
//        echo $res;
    }

    public function getcurl($word) {
//        $word = "下一站天后";
        $url = "http://www.baidu.com/s?wd=".$word;
        // 构造包头，模拟浏览器请求
        $header = array (
            "Host:www.baidu.com",
            "Content-Type:application/x-www-form-urlencoded",//post请求
            "Connection: keep-alive",
            'Referer:http://www.baidu.com',
            'User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; BIDUBrowser 2.6)'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $content = curl_exec ( $ch );
        if ($content == FALSE) {
            echo "error:" . curl_error ( $ch );
        }
        curl_close ( $ch );

        $reg = '/<div class="result c-container " id="1"[\s\S]*?><em>'."$word".'<\/em>/';
        preg_match_all($reg,$content,$match);
        return $match;
//        echo "<pre>";
//        print_r($match);
//        echo "</pre>";
     }

    public function student() {
//        $m = M('student',"","mysql://root:@localhost:3306/tp32#utf8");
        $m = M('student');
        $r = $m->select();
        echo $m->getLastSql();
        echo "<hr>";
        dump($r);
    }

    public function testSearch() {
        $res = $this->getcurl("冰帝校园行");
        echo "<pre>";
        print_r($res);
        echo "</pre>";

        if(empty($res[0])) {
            echo "为空";
        }
    }

    //引入一个类
    public function importClass() {
        echo "<a href='Application/Common/Foo.php'>aaa</a>";//exit;
        require_once 'Application/Common/Foo.php';
//        require_once 'D:\software\xampp\htdocs\thinkphp32\Application\Common\Foo.php';
        $foo = new \Foo();
        echo $foo->test();
        echo "<hr>";
        //获取当前路径
        echo __SELF__;///thinkphp32/index.php/Home/Index/importClass
        //获取物理路径
        $Absolute_Path=$_SERVER['SCRIPT_FILENAME'];
        echo "<hr>";
        echo $Absolute_Path;
    }

    //神马搜索
    public function smSearch() {
//        $link = "http://m.sm.cn/s?q=一等家丁&from=smor&safe=1&snum=6";
//        $link = "http://m.sm.cn/s?q=我的贴身校花&from=smor&safe=1&snum=6";
//        $link = "http://m.sm.cn/s?q=下一站天后";
//        $link = "http://m.sm.cn/s?q=冰帝校园行";
//        $link = "http://m.sm.cn/s?q=凤凰错：替嫁弃妃";
        $link = "http://m.sm.cn/s?q=超级狂少";
        $content = file_get_contents($link);
        echo "<pre>";
        print_r($content);
        echo "</pre>";
        $preg = '/<div class="article ali_row"><h2>(.*?)<em>超级狂少<\/em>/';
        preg_match_all($preg,$content,$match);
        echo "<pre>";
        print_r($match);
        echo "</pre>";
    }

    public function smCurl() {
        $ch = curl_init();
        $timeout = 0; // set to zero for no timeout
        $url = "http://m.sm.cn/s?q=超级狂少";
        curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36');
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $html = curl_exec($ch);
        echo "<pre>";
        print_r($html);
        echo "</pre>";
    }
}