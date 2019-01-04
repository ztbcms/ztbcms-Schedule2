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
    public function checkRule($id, $timePeriod, $month_day, $week_day)
    {
        if (!$timePeriod) return ['status' => false, 'msg' => '时间不能为空'];
        if (!$id) return ['status' => false, 'msg' => '模板id不能为空'];
        $time = explode('-', $timePeriod);
        $start_time = strtotime($time[0]);
        $end_time = strtotime($time[1]);

        if ($month_day && !$week_day) {
            //是否为多个
            if (is_array($month_day)) {
                foreach ($month_day as $k => $v) {
                    $data['items'][$k]['day'] = $v;
                    $data['items'][$k]['loop_type'] = self::TYPE_MONTH;
                }
            } else {
                $data['items'][0]['day'] = $month_day;
                $data['items'][0]['loop_type'] = self::TYPE_MONTH;
            }
        } else if ($week_day && !$month_day) {
            //每周
            if (is_array($week_day)) {
                foreach ($week_day as $k => $v) {
                    $data['items'][$k]['week'] = $v;
                    $data['items'][$k]['loop_type'] = self::TYPE_WEEK;
                }
            } else {
                $data['items'][0]['week'] = $week_day;
                $data['items'][0]['loop_type'] = self::TYPE_WEEK;
            }
        } else if (!$month_day && !$week_day) {
            //每天
            $data['items'][0]['loop_type'] = self::TYPE_DAILY;
        } else {
            if (is_array($month_day)) {
                foreach ($month_day as $k => $v) {
                    $month_data[$k]['day'] = $v;
                    $month_data[$k]['loop_type'] = self::TYPE_MONTH;
                }
            } else {
                $month_data[0]['day'] = $month_day;
                $month_data[0]['loop_type'] = self::TYPE_MONTH;
            }

            if (is_array($week_day)) {
                foreach ($week_day as $k => $v) {
                    $week_data[$k]['week'] = $v;
                    $week_data[$k]['loop_type'] = self::TYPE_WEEK;
                }
            } else {
                $week_data[0]['week'] = $week_day;
                $week_data[0]['loop_type'] = self::TYPE_WEEK;
            }
            $data['items'] = array_merge($week_data, $month_data);
        }

        foreach ($data['items'] as &$v) {
            $v['start_time'] = $start_time;
            $v['end_time'] = $end_time;
            $v['schedule_id'] = $id;
        }
        return ['status' => true, 'msg' => '请求成功', 'data' => $data];
    }

    /*
     * 规则的转义
     */
    public function translateView($data)
    {
        if (!$data['start_time'] && !$data['end_time']) {
            $data['time_name'] = '不限制时间';
        } else {
            $data['time_name'] = date("Y/m/d", $data['start_time']) . '-' . date("Y/m/d", $data['end_time']);
        }
        //每月
        if ($data['loop_type'] == self::TYPE_MONTH) {
            $data['loop_type_name'] = "每个月的" . $data['day'] . '号';
        }
        //每週
        if ($data['loop_type'] == self::TYPE_WEEK) {
            $data['loop_type_name'] = "每周的周" . $data['week'];
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
    public function checkTime($time, $id)
    {
        $where['schedule_id'] = $id;
        $where['start_time'] = ['ELT', $time];
        $where['end_time'] = ['EGT', $time];
        $rule_res = D(self::TABLE)->where($where)->order('sort desc')->select();
        if (!$rule_res) {
            return ['status' => false, 'msg' => '预约不存在', 'code' => '400'];
        }
        $code = '400';
        foreach ($rule_res as &$v) {
            //每月多少号
            if ($v['loop_type'] == self::TYPE_MONTH) {
                $res = $this->checkMonth($time, $v['day']);
            }
            //每天
            if ($v['loop_type'] == self::TYPE_DAILY) {
                $res = true;
            }
            //周几
            if ($v['loop_type'] == self::TYPE_WEEK) {
                $res = $this->checkWeek($time, $v['week']);
            }
            if ($res == true) {
                $data['schedule_id'] = $v['schedule_id'];
                $data['schedule_rule_id'] = $v['id'];
                $data['date'] = date("Y/m/d", $time);
                $msg = '预约存在; schedule_id为：' . $data['schedule_id'] . ',schedule_rule_id为：' . $data['schedule_rule_id'] . ',時間data:為' . $data['date'];
                return ['status' => false, 'msg' => $msg, 'code' => '200', 'data' => $data];
            }
        }

        if ($code != '200') {
            return ['status' => false, 'msg' => '预约不存在', 'code' => '400'];
        }
    }

    /*
     * 校验每个月多少号类型的预约
     */
    protected function checkMonth($time, $day)
    {
        $today = (int)date("d", $time);
        if ($day == $today) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 校验星期几是否有预约
     */
    protected function checkWeek($time, $week)
    {
        $today = date("w", $time);
        if ($today == '0') $today = '7';
        $today = (int)$today;
        if ($week == $today) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 查询有的时间段
     */
    public function queryRule($start_time, $end_time, $id)
    {
        $new = [];
        $rule_where['schedule_id'] = $id;
        $res = D(self::TABLE)->where($rule_where)->select();
        foreach ($res as $k => $v) {
            $data_res = [];
            $b_start_time = date("Y-m-d", $v['start_time']);
            $b_end_time = date("Y-m-d", $v['end_time']);
            if($v['loop_type'] == 'daily'){
                $data_res = self::Date_segmentation($b_start_time,$b_end_time)['days_list'];
            }
            if($v['loop_type'] == 'week'){
                $data_res  = self::getWeeklyBuyDate($b_start_time,$b_end_time,$v['week']);
            }
            if($v['loop_type'] == 'month'){
                $data_res = self::getMonthlyBuyDate($b_start_time,$b_end_time,$v['day']);
            }
            foreach ($data_res as $k2 => $v2){
                if($start_time <= strtotime($v2) && $end_time >= strtotime($v2)){
                    $new[$k][$k2]['data'] = $v2;
                    $new[$k][$k2]['schedule_id'] = $v['schedule_id'];
                    $new[$k][$k2]['schedule_rule_id'] = $v['id'];
                    $new[$k][$k2]['loop_type'] = $v['loop_type'];
                }
            }
        }
        $newhello = [];
        $k = 0;
        foreach ($new as $key => $val) {
            foreach ($val as $key2 => $val2) {
                $newhello[$k]['data'] = $val2 ['data'];
                $newhello[$k]['schedule_id'] = $val2['schedule_id'];
                $newhello[$k]['schedule_rule_id'] = $val2['schedule_rule_id'];
                $newhello[$k]['loop_type'] = $val2['loop_type'];
                $k++;
            }
        }
        return $newhello;
    }

    /**
     * 服务：将时间段按天进行分割
     * @param string $start_date @起始日期('Y-m-d H:i:s')
     * @param string $end_date @结束日期('Y-m-d H:i:s')
     * @return array $mix_time_data=array(
     * 'start_date'=>array([N]'Y-m-d H:i:s'),
     * 'end_date'=>array([N]'Y-m-d H:i:s'),
     * 'days_list'=>array([N]'Y-m-d'),
     * 'days_inline'=>array([N]'Y-m-d H:i:s'),
     * 'times_inline'=>array([N]'time()')
     * )
     */
    protected function Date_segmentation($start_date, $end_date)
    {
        //如果为空，则从今天的0点为开始时间
        if (!empty($start_date))
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
        else
            $start_date = date('Y-m-d 00:00:00', time());
        //如果为空，则以明天的0点为结束时间（不存在24:00:00，只会有00:00:00）
        if (!empty($end_date))
            $end_date = date('Y-m-d H:i:s', strtotime($end_date));
        else
            $end_date = date('Y-m-d 00:00:00', strtotime('+1 day'));
        //between 查询 要求必须是从低到高
        if ($start_date > $end_date) {
            $ttt = $start_date;
            $start_date = $end_date;
            $end_date = $ttt;
        } elseif ($start_date == $end_date) {
            echo '时间输入错误';
            die;
        }
        $time_s = strtotime($start_date);
        $time_e = strtotime($end_date);
        $seconds_in_a_day = 86400;

        //生成中间时间点数组（时间戳格式、日期时间格式、日期序列）
        $days_inline_array = array();
        $times_inline_array = array();

        //日期序列
        $days_list = array();
        //判断开始和结束时间是不是在同一天
        $days_inline_array[0] = $start_date;  //初始化第一个时间点
        $times_inline_array[0] = $time_s;     //初始化第一个时间点
        $days_list[] = date('Y-m-d', $time_s);//初始化第一天
        if (
            date('Y-m-d', $time_s)
            == date('Y-m-d', $time_e)
        ) {
            $days_inline_array[1] = $end_date;
            $times_inline_array[1] = $time_e;
        } else {
            /**
             * A.取开始时间的第二天凌晨0点
             * B.用结束时间减去A
             * C.用B除86400取商，取余
             * D.用A按C的商循环+86400，取得分割时间点，如果C没有余数，则最后一个时间点 与 循环最后一个时间点一致
             */
            $A_temp = date('Y-m-d 00:00:00', $time_s + $seconds_in_a_day);
            $A = strtotime($A_temp);
            $B = $time_e - $A;
            $C_quotient = floor($B / $seconds_in_a_day);    //商舍去法取整
            $C_remainder = fmod($B, $seconds_in_a_day);               //余数
            $days_inline_array[1] = $A_temp;
            $times_inline_array[1] = $A;
            $days_list[] = date('Y-m-d', $A);              //第二天
            for ($increase_time = $A, $c_count_t = 1; $c_count_t <= $C_quotient; $c_count_t++) {
                $increase_time += $seconds_in_a_day;
                $days_inline_array[] = date('Y-m-d H:i:s', $increase_time);
                $times_inline_array[] = $increase_time;
                $days_list[] = date('Y-m-d', $increase_time);
            }
            $days_inline_array[] = $end_date;
            $times_inline_array[] = $time_e;
        }

        return array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'days_list' => $days_list,
            'days_inline' => $days_inline_array,
            'times_inline' => $times_inline_array
        );
    }

    
    /**
     * desc 获取每周X执行的所有日期
     * @param string $start 开始日期, 2016-10-17
     * @param string $end 结束日期, 2016-10-17
     * @param int $weekDay 1~5
     * @return array
     */
    protected function getWeeklyBuyDate($start, $end, $weekDay)
    {
        //获取每周要执行的日期 例如: 2016-01-02
        $start = empty($start) ? date('Y-m-d') : $start;
        $startTime = strtotime($start);
        $startDay = date('N', $startTime);
        if ($startDay < $weekDay) {
            $startTime = strtotime(self::$WORK_DAY[$weekDay]['en'], strtotime($start)); //本周x开始, 例如, 今天(周二)用户设置每周四执行, 那本周四就会开始执行
        } else {
            $startTime = strtotime('next '.self::$WORK_DAY[$weekDay]['en'], strtotime($start));//下一个周x开始, 今天(周二)用户设置每周一执行, 那应该是下周一开始执行
        }
        $endTime = strtotime($end);
        $list = [];
        for ($i=0;;$i++) {
            $dayOfWeek = strtotime("+{$i} week", $startTime); //每周x
            if ($dayOfWeek > $endTime) {
                break;
            }
            $list[] = date('Y-m-d', $dayOfWeek);
        }
        return $list;
    }

    /**
     * desc 获取每月X号执行的所有日期
     * @param string $start 开始日期, 2016-10-17
     * @param string $end 结束日期, 2016-10-17
     * @param int $monthDay 1~28
     * @return array
     */
    protected function getMonthlyBuyDate($start, $end, $monthDay)
    {
        $monthDay = str_pad($monthDay, 2, '0', STR_PAD_LEFT); //左边补零
        $start = empty($start) ? date('Y-m-d') : $start;
        $startTime = strtotime($start);
        $startDay = substr($start, 8, 2);
        if (strcmp($startDay, $monthDay) < 0) {
            $startMonthDayTime = strtotime(date('Y-m-', strtotime($start)).$monthDay);
            //本月开始执行, 今天(例如,26号)用户设置每月28号执行, 那么本月就开始执行
        } else  {
            $startMonthDayTime = strtotime(date('Y-m-', strtotime('+1 month', $startTime)).$monthDay); //从下个月开始
        }
        $endTime = strtotime($end);
        $list = [];
        for ($i=0;;$i++) {
            $dayOfMonth = strtotime("+{$i} month", $startMonthDayTime);//每月x号
            if ($dayOfMonth > $endTime) {
                break;
            }
            $list[] = date('Y-m-d', $dayOfMonth);
        }
        return $list;
    }
    
    public static $WORK_DAY = [
        1 => ['en' => 'Monday', 'cn' => '一'],
        2 => ['en' => 'Tuesday', 'cn' => '二'],
        3 => ['en' => 'Wednesday', 'cn' => '三'],
        4 => ['en' => 'Thursday', 'cn' => '四'],
        5 => ['en' => 'Friday', 'cn' => '五'],
        6 => ['en' => 'Saturday' ,'cn' => '六'],
        7 => ['en' => 'Sunday' ,'cn' => '七']
    ];

}