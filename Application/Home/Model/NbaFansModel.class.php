<?php
/**
 * Created by PhpStorm.
 * User: linjiabei
 * Date: 2018/4/16
 * Time: 14:31
 */
namespace Home\Model;
use Think\Model;
class NbaFansModel extends Model{
//    protected $insertFields = 'nickname,nba_team,idiom,wish,add_time';
    //自动验证
    protected $_validate = array(
        array('nickname','require','昵称不能为空！',1),//验证昵称不能为空
        array('nba_team','require','NBA季后赛球队名称不能为空！'),//验证NBA季后赛球队不能为空
        array('idiom','3,6','成语必须大于3个字符小于6个字符！',1,'length'),//验证成语3-6
        array('wish','5,30','祝福语必须大于5个字符小于30个字符！',1,'length'),//验证祝福语5-30
//        array('add_time','time',0,'function'),//对add_time字段在更新的时候写入当前时间戳
    );

    //自动完成
    protected $_auto = array (
//        array('status','1'),  // 新增的时候把status字段设置为1
//        array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
//        array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
//        array('add_time','time',1,'function'),// 对add_time字段在更新的时候写入当前时间戳
//    array('add_time','1523870852'),
    );
}