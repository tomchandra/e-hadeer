<?php

/**
 * Controller untuk laporan
 * @param  mixed
 * @return array
 */

namespace App\Controllers;

use App\Models\M_Employee;
use App\Models\M_Presence;

class Report extends BaseController
{
    protected $M_Presence;
    protected $M_Employee;

    public function __construct()
    {
        $this->M_Presence = new M_Presence();
        $this->M_Employee = new M_Employee();
    }

    public function index($report)
    {
        if ($report == "presence") {
            return view('app/report_presence', $this->param_data);
        }
    }

    /**
     * Method untuk menampilkan data presensi berdasarkan bulan
     * @param date date
     * @return array
     */
    public function get_all_presence_by_month()
    {
        if ($this->request->isAJAX()) {
            $choose_date      = $this->request->getGet('date');
            $public_holiday   = json_decode(file_get_contents("https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json"), true);
            $end_day_of_month = date("t", strtotime($choose_date));
            $start_date       = date("Y-m-01", strtotime($choose_date));
            $end_date         = date("Y-m-${end_day_of_month}", strtotime($choose_date));

            $presence_data    = $this->M_Presence->app_getPresenceByRangeDate($start_date, $end_date);
            $employee_data    = $this->M_Employee->app_getDataListEmployee();
            $arr_off_day      = ["Saturday", "Sunday"];
            $arr_day          = [];
            $arr_attend_day   = [];
            $arr_month_attend  = [];
            $arr_employee      = [];
            $arr_early_home    = [];
            $arr_late_home     = [];
            $arr_early_work    = [];
            $arr_late_work     = [];

            // initial day to all employee
            foreach ($employee_data as $employee) {
                $arr_employee[] = $employee;
                for ($day = 1; $day <= $end_day_of_month; $day++) {
                    if ($day < 10) $day = join(["0", $day]);
                    $arr_day[]    = $day;
                    $current_date = date("Y-m-${day}", strtotime($choose_date));
                    $day_name     = date("l", strtotime($current_date));
                    $value        = date("Ymd", strtotime($current_date));
                    $is_holiday   = 0;
                    $clock_in     = null;
                    $clock_out    = null;
                    $marked_as    = null;

                    // absent
                    $diff = strtotime($current_date) - strtotime(date("Y-m-d"));
                    if ($diff < 0) {
                        $clock_in   = "A";
                        $clock_out  = "A";
                        $marked_as  = "marked_as_absent";
                    }
                    // off day
                    if (in_array($day_name, $arr_off_day)) {
                        $is_holiday = 1;
                        $clock_in   = "OFF";
                        $clock_out  = "OFF";
                        $marked_as  = null;
                    }

                    // public holiday
                    if (isset($public_holiday[$value])) {
                        $is_holiday = 1;
                        $clock_in   = "PH";
                        $clock_out  = "PH";
                        $marked_as  = null;
                    }

                    $arr_attend_day[$employee["user_id"]][$day][] = [
                        "is_holiday" => $is_holiday,
                        "clock_in"   => $clock_in,
                        "clock_out"  => $clock_out,
                        "marked_as"  => $marked_as,
                    ];

                    $arr_early_work[$employee["user_id"]] = [];
                    $arr_late_work[$employee["user_id"]]  = [];
                    $arr_early_home[$employee["user_id"]] = [];
                    $arr_late_home[$employee["user_id"]]  = [];
                }
            }


            if (count($presence_data) > 0) {
                // get employee presence
                foreach ($presence_data as $item) {
                    $date = date("d", strtotime($item['working_days']));
                    if (isset($arr_attend_day[$item["user_id"]][$date][0])) {

                        if ($arr_attend_day[$item["user_id"]][$date][0]["is_holiday"] == "0") {
                            $arr_attend_day[$item["user_id"]][$date][0]["clock_in"]   = is_null($item["clock_in"]) ? "00:00" : date("H:i", strtotime($item["clock_in"]));
                            $arr_attend_day[$item["user_id"]][$date][0]["clock_out"]  = is_null($item["clock_out"]) ? "00:00" : date("H:i", strtotime($item["clock_out"]));

                            // get total early_home, late_home, early_work, late_work
                            $date_work = date("Y-m-d", strtotime($item['working_days']));
                            $work_in   = "08:00";
                            $work_out  = "16:00";
                            $time_in   = date("H:i", strtotime($item['clock_in']));
                            $time_out  = date("H:i", strtotime($item['clock_out']));

                            $diff_in   = $this->diff_time("${date_work} ${work_in}:00", "${date_work} ${time_in}:00");
                            $diff_out  = $this->diff_time("${date_work} ${work_out}:00", "${date_work} ${time_out}:00");

                            if ($diff_in["str_time"] > 0) {
                                $arr_early_work[$item["user_id"]][] = $diff_in["time"];
                            } else {
                                $arr_late_work[$item["user_id"]][]  = $diff_in["time"];
                            }

                            if ($diff_out["str_time"] > 0) {
                                $arr_early_home[$item["user_id"]][] = $diff_out["time"];
                            } else {
                                $arr_late_home[$item["user_id"]][]  = $diff_out["time"];
                            }
                        }
                    }
                }
            }

            // set data to employee
            foreach ($arr_employee as $employee) {
                $arr_month_attend[] = [
                    "user_id"     => $employee["user_id"],
                    "person_name" => $employee["person_name"],
                    "departement" => $employee["departement_cd"],
                    "job"         => $employee["job_cd"],
                    "presence"    => $arr_attend_day[$employee["user_id"]],
                    "early_home"  => $this->sum_time($arr_early_home[$employee["user_id"]]),
                    "late_home"   => $this->sum_time($arr_late_home[$employee["user_id"]]),
                    "early_work"  => $this->sum_time($arr_early_work[$employee["user_id"]]),
                    "late_work"   => $this->sum_time($arr_late_work[$employee["user_id"]]),
                ];
            }

            // dd($arr_month_attend);

            $this->param_data["day_in_month"]  = array_unique($arr_day);
            $this->param_data["month_attend"]  = $arr_month_attend;

            return view('data/presence_by_month', $this->param_data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk menghitung selisih jam
     * @param time work_time
     * @param time clock_time
     * @return array
     */
    public function diff_time($str_work_time, $str_clock_time)
    {
        $work_time   = new \DateTime("${str_clock_time}");
        $clock_time  = new \DateTime("${str_work_time}");
        $interval    = $work_time->diff($clock_time);
        $diff_in     = $interval->format('%H:%i');
        $str_time    = strtotime($str_work_time) - strtotime($str_clock_time);
        $time        = $str_time != 0 ? date("H:i", strtotime($diff_in)) : null;
        return [
            "time"     => $time,
            "str_time" => $str_time,
        ];
    }

    /**
     * Method untuk menjumlahkan jam
     * @param array times
     * @return array
     */
    public function sum_time($times)
    {
        $all_seconds = 0;
        foreach ($times as $time) {
            if (!is_null($time)) {
                list($hour, $minute) = explode(':', $time);
                $all_seconds += $hour * 3600;
                $all_seconds += $minute * 60;
            }
        }

        $total_minutes = floor($all_seconds / 60);
        $hours = floor($total_minutes / 60);
        $minutes = $total_minutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
