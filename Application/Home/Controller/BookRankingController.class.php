<?php
/**
 * Created by PhpStorm.
 * User: linjiabei
 * Date: 2018/5/3
 * Time: 9:35
 */
namespace Home\Controller;
use Think\Controller;
class BookRankingController extends Controller {
    public function index() {
        $d = D("BookRanking");
        //查找条件：字数超过50万的
        $map['words_count'] = array('gt','500000');
        $r = $d->where($map)->select();
        //循环结结果查询
        foreach ($r as $k=>$v) {
            $url = "https://m.baidu.com/s?word={$v['book_name']}";
            $str = $this->GetcURL($url);
            //判断书名是否带冒号：
            if(strpos($v['book_name'],"：")) {
                $str1 = explode("：",$v['book_name']);
                $reg = '/<div class="c-result result" srcid="nvl_flow" new_srcid="[\s\S]*?" order="1" tpl="nvl_flow" [\s\S]*?<div class="c-result-content">[\s\S]*?'.'<em>'.$str1[0].'<\/em>'.'：'.'<em>'.$str1[1].'<\/em>'.'[\s\S]*?<span class="c-gap-right" data-a-339f6d90 data-a-4498becf>(3G书城)<\/span>/';
            }else{
                $reg = '/<div class="c-result result" srcid="nvl_flow" new_srcid="[\s\S]*?" order="1" tpl="nvl_flow" [\s\S]*?<div class="c-result-content">[\s\S]*?<em>'.$v['book_name'].'<\/em>[\s\S]*?<span class="c-gap-right" data-a-339f6d90 data-a-4498becf>(3G书城)<\/span>/';
            }
            //正则匹配
            $match = $this->regExp($reg,$str);
            echo "<pre>";
            print_r($match);
            echo "</pre>";
            //根据返回的结果判断是否为第一，接着更新数据库
            if(!empty($match[0][0]) && ($match[1][0] == '3G书城')) {
                $data['is_top'] = "是";
                $condition['book_name'] = $v['book_name'];
                $d->where($condition)->save($data);
                echo $d->getLastSql();
                echo "<hr>";
            }else{
                $data['is_top'] = "否";
                $condition['book_name'] = $v['book_name'];
                $d->where($condition)->save($data);
                echo $d->getLastSql();
            }
        }
//        echo "<pre>";
//        print_r($r);
//        echo "</pre>";
    }

    //cURL get方法获取
    public function GetcURL($url) {
        $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);//0表示不输出Header，1表示输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    //正则匹配
    public function regExp($reg,$str) {
        preg_match_all($reg,$str,$mat);

        return $mat;
    }

    //test
    public function test() {
        $d = D('BookRanking');
        $condition['book_name'] = '我的贴身校花';
        $data['is_top'] = "是";
        echo $d->where($condition)->save($data);
        echo "<hr>";
        echo $d->getLastSql();
    }
}