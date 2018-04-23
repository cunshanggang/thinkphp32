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
                    $currentSheet = $PHPExcel->getSheet(0);                      // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
                    $allColumn = $currentSheet->getHighestColumn();              // 获取总列数
                    $allRow = $currentSheet->getHighestRow();                    // 获取总行数
                    $data=array();
                    for($j=1;$j<=$allRow;$j++){
                        //从A列读取数据
                        for($k='A';$k<=$allColumn;$k++){
                            // 读取单元格
                            $data[$j][]=$PHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                        }
                    }
                }
            }
            echo "<pre>";
            print_r($data);
            echo "</pre>";

        }
    }
}