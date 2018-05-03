<?php
namespace Attendance\Controller;
use Think\Controller;
use Think\Upload;
class IndexController extends Controller {
    public function index(){
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }

    public function test() {
        $data=array();
        if(!empty($_FILES)) {
            $upload = new Upload();
//            $upload->maxSize = 3145728 ;//设置附件上传大小
            $upload->exts = array('xls','xlsx');//设置附件上传类型
            $upload->rootPath = './Public/upload/excel/';//设置附件上传子目录
            $upload->autoSub   = false;//将自动生成以photo后面加时间的形式文件夹，关闭
            //上传文件
            $info = $upload->upload();
            $exts = $info['file']['ext'];//获取文件的后缀名
            $filename = $upload->rootPath.$info['file']['savename'];//生成文件路径名
            if(!$info) {
                $this->error($upload->getError());
            }else{
//                header('content-Type:application/vnd.ms-excel;charset=utf-8');
                vendor("PHPExcel.PHPExcel");//导入PHPExcel类
                $PHPReader = new \PHPExcel();//创建PHPExcel对象，注意，不能少了 \
                if($exts == 'xls') {
                    vendor("PHPExcel.PHPExcel.Reader.Excel5");
                    $PHPReader = new \PHPExcel_Reader_Excel5();
                }else{
                    if($exts == 'xlsx') {
                        vendor("PHPExcel.PHPExcel.Reader.Excel2007");
                        $PHPReader = new \PHPExcel_Reader_Excel2007();
                    }
                    $PHPExcel=$PHPReader->load($filename);
                    $currentSheet = $PHPExcel->getSheet(0);//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
                    $allColumn = $currentSheet->getHighestColumn();//获取总列数
                    $allRow = $currentSheet->getHighestRow();//获取总行数
                    for($j=1;$j<=$allRow;$j++){
                        //从A列读取数据
                        for($k='A';$k<=$allColumn;$k++){
                            // 读取单元格
                            $data[$j][]=$PHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                        }
                    }
                }
            }
        }
        //去掉标题并重置索引
         array_shift($data);
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";
        $r = $this->assembleData($data);
        $m = array_values($r['morning']);
        $a = array_values($r['afternoon']);
//        echo "<pre>";
//        print_r($m);
//        echo "<hr>";
//        print_r($a);
//        echo "</pre>";
        //将数据索引改为与数据库字段对应
        $am = array();
        foreach($m as $k1=>$v1) {
            $am[$k1]['name'] = $v1[0];
            $am[$k1]['time'] = $v1[1];
            $am[$k1]['flag'] = $v1[2];
        }
//        echo "<pre>";
//        print_r($am);
//        echo "</pre>";
//        echo "<hr>";
        $d = D('Attendance');
        $d->addAll($am);

        //下午
        $pm = array();
        foreach($a as $k2=>$v2) {
            $pm[$k2]['name'] = $v2[0];
            $pm[$k2]['time'] = $v2[1];
            $pm[$k2]['flag'] = $v2[2];
        }
        $d->addAll($pm);
//        echo "<pre>";
//        print_r($pm);
//        echo "</pre>";
    }

    public function search() {

        $this->display();
    }

    public function searchData() {
//        echo "<pre>";
//        print_r($_POST);
//        echo "</pre>";
        $start    = $_POST['start'];
        $end      = $_POST['end'];
        $username = $_POST['username'];
        $result   = $this->timetostamp($start,$end);
//        echo "<pre>";
//        print_r($result);
//        echo "</pre>";
        $a = D('Attendance');
        $data = array();
        foreach ($result as $k=>$v) {
            $deadline    = $v + 86340;
            $map['name'] = $username;
            $map['time'] = array("between","$v,$deadline");
            $r = $a->where($map)->select();
            if(!empty($r)) {
                $w = date("w",$v);
                switch ($w) {
                    case 1 :
                        $r['week'] = "星期一";
                        break;
                    case 2 :
                        $r['week'] = "星期二";
                        break;
                    case 3 :
                        $r['week'] = "星期三";
                        break;
                    case 4 :
                        $r['week'] = "星期四";
                        break;
                    case 5 :
                        $r['week'] = "星期五";
                        break;
                    case 6 :
                        $r['week'] = "星期六";
                        break;
                    default :
                        $r['week'] = "星期日";
                        break;
                }
                $r['date']   = date('Y-m-d',$v);
                $data[$k] = $r;
            }

        }
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
        if(!empty($data)) {
            $this->ajaxReturn($data);
        }else{
            $this->ajaxReturn(0);
        }



//        exit($_POST['username']);
//        exit;
//        return $_REQUEST['username'];
    }

    //将时间转换成时间戳格式
    public function timetostamp($s,$e) {
        //设置默认的时区
        date_default_timezone_set("PRC");
        $start = new \DateTime($s);
        $end   = new \DateTime($e);
        // 时间间距 这里设置的是一天
        $interval = \DateInterval::createFromDateString('1 day');
        $period   = new \DatePeriod($start, $interval, $end);

        $timeStamp = array();
        foreach ($period as $dt) {
            array_push($timeStamp,strtotime($dt->format("Y-m-d")));
        }

        return $timeStamp;
    }

    public function assembleData($arr) {
//        $arr = array(
//            '0'=>array('小明',"2018-03-26 上午 06:43:07"),
//            '1'=>array('小明',"2018-03-26 上午 08:43:07"),
//            '2'=>array('小明',"2018-03-26 下午 06:43:07"),
//            '3'=>array('小明',"2018-03-26 下午 07:43:07")
//        );

        //将后面的时间转换为时间戳
        foreach ($arr as $k=>$v) {
            if(strstr($v[1],"上午")) {
                $arr[$k][1] = strtotime(str_replace(" 上午 "," ",$v[1]));
                //区分上午,0:表示上午
                $arr[$k][2] = 0;
            }
            if(strstr($v[1],"下午")) {
                $arr[$k][1] = strtotime(str_replace(" 下午 "," ",$v[1]))+60*60*12;
                //判断是否是12点打卡，如果是，就不用加上12个小时
                $stamp = strtotime(str_replace(" 下午 "," ",$v[1]));
                $hour  = date("h",$stamp);
                if($hour == 12) {
                    $arr[$k][1] = $stamp;
                }
                //区分下午,1:表示下午
                $arr[$k][2] = 1;
            }
        }

//        echo "<pre>";
//        print_r($arr);
//        echo "</pre>";
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
        //将索引重置
        $morning = array_values($morning);
        $afternoon = array_values($afternoon);
//        echo "<pre>";
//        print_r(array_values($morning));
//        echo "<hr>";
//        print_r(array_values($afternoon));
//        echo "</pre>";
        //查找是否有重复的
        foreach($morning as $k2=>$v2) {
            $name = $v2[0];
            $time = $v2[1];
            $date = date("Ymd",$v2[1]);
            foreach($morning as $k3=>$v3) {
                $name1 = $v3[0];
                $time1 = $v3[1];
                $date1 = date("Ymd",$v3[1]);
                if(($name==$name1) && ($time1>$time) && ($date==$date1)) {
                    unset($morning[$k3]);
                }
            }
        }
//        echo "<pre>";
//        print_r($morning);
//        echo "</pre>";
//        echo "<hr>";
        foreach($afternoon as $k4=>$v4) {
            $name2 = $v4[0];
            $time2 = $v4[1];
            $date2 = date("Ymd",$v4[1]);
            foreach($afternoon as $k5=>$v5) {
                $name3 = $v5[0];
                $time3 = $v5[1];
                $date3 = date("Ymd",$v5[1]);
                if(($name2==$name3) && ($time3>$time2) && ($date2==$date3)) {
                    unset($afternoon[$k4]);
                }
            }
        }
//        echo "<pre>";
//        print_r($afternoon);
//        echo "</pre>";
        $result['morning']   = $morning;
        $result['afternoon'] = $afternoon;

        return $result;
    }
}