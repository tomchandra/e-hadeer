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
            $arr_day          = [];
            $arr_attend_day   = [];
            $arr_month_attend  = [];

            if (count($presence_data) > 0) {
                // get employee presence
                foreach ($presence_data as $item) {
                    $date = date("d", strtotime($item['working_days']));
                    $arr_attend_day[$item["user_id"]][$date][] = [
                        "is_holiday" => null,
                        "clock_in"   => is_null($item["clock_in"]) ? null : date("H:i", strtotime($item["clock_in"])),
                        "clock_out"  => is_null($item["clock_out"]) ? null : date("H:i", strtotime($item["clock_out"])),
                    ];
                }

                // set employee presence not in date
                foreach ($presence_data as $item) {
                    if (count($arr_attend_day) > 0) {
                        for ($day = 1; $day <= $end_day_of_month; $day++) {
                            if ($day < 10) $day = join(["0", $day]);
                            if (!array_key_exists($day, $arr_attend_day[$item["user_id"]])) {
                                $arr_attend_day[$item["user_id"]][$day][] = [
                                    "is_holiday" => null,
                                    "clock_in"   => null,
                                    "clock_out"  => null,
                                ];
                            }
                        }
                    }
                }
            }

            foreach ($employee_data as $employee) {
                // set employee not in presence
                if (!array_key_exists($employee["user_id"], $arr_attend_day)) {
                    for ($day = 1; $day <= $end_day_of_month; $day++) {
                        if ($day < 10) $day = join(["0", $day]);
                        $arr_day[] = $day;
                        $arr_attend_day[$employee["user_id"]][$day][] = [
                            "is_holiday" => null,
                            "clock_in"   => null,
                            "clock_out"  => null,
                        ];
                    }
                }

                // set day off / ph
                if (count($arr_attend_day) > 0) {
                    for ($day = 1; $day <= $end_day_of_month; $day++) {
                        if ($day < 10) $day = join(["0", $day]);
                        $current_date = date("Y-m-${day}", strtotime($choose_date));
                        $day_name     = date("l", strtotime($current_date));
                        $value        = date("Ymd", strtotime($current_date));

                        if ($day_name == "Saturday" || $day_name == "Sunday" || isset($public_holiday[$value])) {
                            $arr_attend_day[$employee["user_id"]][$day][0]["is_holiday"] = 1;
                            $arr_attend_day[$employee["user_id"]][$day][0]["clock_in"]   = null;
                            $arr_attend_day[$employee["user_id"]][$day][0]["clock_out"]  = null;
                        }
                    }
                }

                $arr_month_attend[] = [
                    "user_id"     => $employee["user_id"],
                    "person_name" => $employee["person_name"],
                    "departement" => $employee["departement_cd"],
                    "job"         => $employee["job_cd"],
                    "presence"    => $arr_attend_day[$employee["user_id"]],
                ];
            }

            dd($arr_month_attend);
            $this->param_data["day_in_month"]  = $arr_day;
            $this->param_data["month_attend"]  = $arr_month_attend;

            return view('data/presence_by_month', $this->param_data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
