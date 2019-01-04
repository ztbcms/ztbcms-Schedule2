<?php
/**
 * Created by PhpStorm.
 * User: cycle_3
 * Email: 953006367@qq.com
 * Date: 2018/12/29
 * Time: 16:07
 */

namespace Schedule\Model;

use Common\Model\Model;

class ScheduleRuleModel extends Model
{

    const TABLE = 'schedule_rule';

    protected $tableName = 'schedule_rule';

    const TYPE_MONTH = 'month';  //每个月
    const TYPE_WEEK = 'week';    //每周
    const TYPE_DAILY = 'daily';  //每天

    /*
     *  校驗規則
     */
    public function checkRule($id,$timePeriod, $month_day, $week_day)
    {
        if (!$timePeriod) return ['status' => false, 'msg' => '时间不能为空'];
        if (!$id) return ['status' => false, 'msg' => '模板id不能为空'];
        $time = explode('-',$timePeriod);
        $start_time = strtotime($time[0]);
        $end_time = strtotime($time[1]);

        if($month_day && !$week_day){
            //是否为多个
            if(is_array($month_day)){
                foreach ($month_day as $k => $v){
                    $data['items'][$k]['day'] = $v;
                    $data['items'][$k]['loop_type'] = self::TYPE_MONTH;
                }
            } else {
                $data['items'][0]['day'] = $month_day;
                $data['items'][0]['loop_type'] = self::TYPE_MONTH;
            }
        } else if($week_day && !$month_day){
            //每周
            if(is_array($week_day)){
                foreach ($week_day as $k => $v){
                    $data['items'][$k]['week'] = $v;
                    $data['items'][$k]['loop_type'] = self::TYPE_WEEK;
                }
            } else {
                $data['items'][0]['week'] = $week_day;
                $data['items'][0]['loop_type'] = self::TYPE_WEEK;
            }
        } else if(!$month_day && !$week_day){
            //每天
            $data['items'][0]['loop_type'] = self::TYPE_DAILY;
        } else {
            if(is_array($month_day)){
                foreach ($month_day as $k => $v){
                    $month_data[$k]['day'] = $v;
                    $month_data[$k]['loop_type'] = self::TYPE_MONTH;
                }
            } else {
                $month_data[0]['day'] = $month_day;
                $month_data[0]['loop_type'] = self::TYPE_MONTH;
            }

            if(is_array($week_day)){
                foreach($week_day as $k => $v){
                    $week_data[$k]['week'] = $v;
                    $week_data[$k]['loop_type'] = self::TYPE_WEEK;
                }
            } else {
                $week_data[0]['week'] = $week_day;
                $week_data[0]['loop_type'] = self::TYPE_WEEK;
            }
            $data['items'] = array_merge($week_data,$month_data);
        }

        foreach ($data['items'] as &$v){
            $v['start_time'] = $start_time;
            $v['end_time'] =  $end_time;
            $v['schedule_id'] = $id;
        }
        return ['status' => true, 'msg' => '请求成功','data'=>$data];
    }

    /*
     * 规则的转义
     */
    public function translateView($data){
        if(!$data['start_time'] && !$data['end_time']){
            $data['time_name'] = '不限制时间';
        } else {
            $data['time_name'] = date("Y/m/d",$data['start_time']).'-'.date("Y/m/d",$data['end_time']);
        }
        //每月
        if ($data['loop_type'] == self::TYPE_MONTH) {
            $data['loop_type_name'] = "每个月的".$data['day'].'号';
        }
        //每週
        if ($data['loop_type'] == self::TYPE_WEEK) {
            $data['loop_type_name'] = "每周的周".$data['week'];
        }
        //每天
        if ($data['loop_type'] == self::TYPE_DAILY) {
            $data['loop_type_name'] = "每天";
        }
        return $data;
    }

    /*
     * 校验时间是否被预约
     */
    public function checkTime($time,$id){
        $where['schedule_id'] = $id;
        $where['start_time'] = ['ELT',$time];
        $where['end_time'] = ['EGT',$time];
        $rule_res = D(self::TABLE)->where($where)->select();
        if(!$rule_res) {
            return ['status' => false, 'msg' => '预约不存在' , 'code'=>'400'];
        }
        $code = '400';
        foreach($rule_res as &$v){
            //每月多少号
            if($v['loop_type'] == self::TYPE_MONTH){
                $res = $this->checkMonth($time,$v['day']);
            }
            //每天
            if($v['loop_type'] == self::TYPE_DAILY){
                $res = true;
            }
            //周几
            if($v['loop_type'] == self::TYPE_WEEK){
                $res = $this->checkWeek($time,$v['week']);
            }
            if($res == true) {
                $data['schedule_id'] = $v['schedule_id'];
                $data['schedule_rule_id'] = $v['id'];
                $msg = '预约存在; schedule_id为：'.$data['schedule_id'].',schedule_rule_id为：'.$data['schedule_rule_id'];
                return ['status' => false, 'msg' => $msg , 'code'=>'200','data'=>$data];
            }
        }

        if($code != '200'){
            return ['status' => false, 'msg' => '预约不存在' , 'code'=>'400'];
        }
    }

    /*
     * 校验每个月多少号类型的预约
     */
    protected  function checkMonth($time,$day){
        $today = (int)date("d",$time);
        if($day == $today){
            return true;
        } else {
            return false;
        }
    }

    /*
     * 校验星期几是否有预约
     */
    protected function checkWeek($time,$week){
        $today = date("w",$time);
        if($today == '0') $today = '7';
        $today = (int)$today;
        if($week == $today){
            return true;
        } else {
            return false;
        }
    }

}