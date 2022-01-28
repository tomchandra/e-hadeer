<?php

/**
 * Controller untuk presensi
 * @param  mixed
 * @return array
 */

namespace App\Controllers;

use App\Models\M_Presence;

class Presence extends BaseController
{
    protected $M_Presence;

    public function __construct()
    {
        $this->M_Presence = new M_Presence();
    }

    public function index()
    {
        $user_id    = $this->session->get('user_id');
        $clock_in   = $this->M_Presence->selectMax('presence_dttm')->where('date(presence_dttm)', date("Y-m-d"))->where('user_id', $user_id)->where('presence_type', 'clock_in')->get()->getResultArray()[0];
        $clock_out  = $this->M_Presence->selectMax('presence_dttm')->where('date(presence_dttm)', date("Y-m-d"))->where('user_id', $user_id)->where('presence_type', 'clock_out')->get()->getResultArray()[0];

        $this->param_data["clock_in"]         = is_null($clock_in["presence_dttm"]) ? "" : date("d F Y H:i:s", strtotime($clock_in["presence_dttm"]));
        $this->param_data["clock_out"]        = is_null($clock_out["presence_dttm"]) ? "" : date("d F Y H:i:s", strtotime($clock_out["presence_dttm"]));

        return view('app/presence', $this->param_data);
    }


    /**
     * Method untuk upload gambar presensi
     * @param mixed file
     * @return array
     */
    public function upload_image_presence()
    {
        if ($this->request->getFile('webcam')) {
            try {

                $filename = 'pic_' . date('YmdHis') . '.jpeg';
                $file     = $this->request->getFile('webcam');

                $file->move('upload/file/', $filename);
                $this->session->set('user_file_name', $filename);
                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        }
    }

    /**
     * Method untuk menyimpan presensi
     * @param string presence_type
     * @param date presence_dttm
     * @param int user_id
     * @param mixed presence_image
     * @return array
     */
    public function save_presence()
    {
        if ($this->request->isAJAX()) {
            try {

                $data = [
                    "presence_type"   => $this->request->getPost('presence_type'),
                    "presence_dttm"   => date("Y-m-d H:i:s"),
                    "user_id"         => $this->session->get('user_id'),
                    "presence_image"  => $this->session->get('user_file_name'),
                ];

                $this->M_Presence->insert($data);
                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        }
    }

    /**
     * Method untuk mengambil data presensi berdasarkan user dan bulan
     * @param int user_id
     * @param date date
     * @return array
     */
    public function get_curent_monthyear_presence_byuser()
    {
        if ($this->request->isAJAX()) {
            date_default_timezone_set('Asia/Jakarta');
            $date             = $this->request->getGet('date');
            $user_id          = $this->session->get('user_id');
            $public_holiday   = json_decode(file_get_contents("https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json"), true);
            $end_day_of_month = date("t", strtotime($date));
            $data             = $this->M_Presence->app_getCurrentMonthYearPresenceByUser($user_id, $date);
            $presence         = array();
            $reset_time       = "00:00";
            $work_in          = "08:00";
            $work_out         = "16:00";
            $arr_off_day      = ["Saturday", "Sunday"];
            $early_home       = [];
            $late_work        = [];
            $arr_offday       = [];

            for ($day = 1; $day <= $end_day_of_month; $day++) {
                if ($day < 10) $day = "0" . $day;
                $presence[$day] = [
                    "date"              => date("${day}-m-Y", strtotime($date)),
                    "work_in"           => $work_in,
                    "work_out"          => $work_out,
                    "clock_in"          => null,
                    "clock_out"         => null,
                    "early_work"        => null,
                    "late_work"         => null,
                    "early_home"        => null,
                    "late_home"         => null,
                    "description"       => null,
                    "is_holiday"        => null,
                    "class_holiday"     => null,
                ];
            }

            if (count($data) > 0) {
                foreach ($data as $item) {
                    $day  = date('d', strtotime($item['presence_dttm']));
                    $date = date("Y-m-d", strtotime($item["presence_dttm"]));
                    $time = date("H:i", strtotime($item["presence_dttm"]));

                    if ($item["presence_type"] == "clock_in") {
                        $presence[$day]["clock_in"] = $time;
                        $diff_in = $this->diff_time("${date} ${work_in}:00", "${date} ${time}:00");

                        if ($diff_in["str_time"] > 0) {
                            $presence[$day]["early_work"]       = $diff_in["time"];
                        } else {
                            $presence[$day]["late_work"]        = $diff_in["time"];
                            array_push($late_work, $diff_in["time"]);
                        }
                    }

                    if ($item["presence_type"] == "clock_out") {
                        $presence[$day]["clock_out"] = $time;
                        $diff_out = $this->diff_time("${date} ${work_out}:00", "${date} ${time}:00");

                        if ($diff_out["str_time"] > 0) {
                            $presence[$day]["early_home"]       = $diff_out["time"];
                            array_push($early_home, $diff_out["time"]);
                        } else {
                            $presence[$day]["late_home"]        = $diff_out["time"];
                        }
                    }
                }
            }

            foreach ($presence as $item) {
                for ($day = 1; $day <= $end_day_of_month; $day++) {
                    if ($day < 10) $day = "0" . $day;
                    $current_date = date("Y-m-${day}", strtotime($date));
                    $day_name     = date("l", strtotime($current_date));
                    $value        = date("Ymd", strtotime($current_date));

                    if (in_array($day_name, $arr_off_day) || isset($public_holiday[$value])) {
                        $arr_offday[] = $day;
                        $presence[$day]["work_in"]      = null;
                        $presence[$day]["work_out"]     = null;
                        $presence[$day]["clock_in"]     = null;
                        $presence[$day]["clock_out"]    = null;
                        $presence[$day]["early_work"]   = null;
                        $presence[$day]["late_work"]    = null;
                        $presence[$day]["early_home"]   = null;
                        $presence[$day]["late_home"]    = null;

                        $presence[$day]["is_holiday"]    = 1;
                        $presence[$day]["class_holiday"] = "bg-secondary";
                        $presence[$day]["description"]   = "Day Off";

                        if (isset($public_holiday[$value])) {
                            $presence[$day]["description"] = "PH (" . $public_holiday[$value]["deskripsi"] . ")";
                        }
                    } else {
                    }
                }
                $diff = strtotime($item["date"]) - strtotime(date("Y-m-d"));
                if ($diff < 0) {
                    $day_active  = date("d", strtotime($item['date']));
                    if (!in_array($day_active, $arr_offday)) {

                        if (!is_null($item["clock_out"]) && is_null($item["clock_in"])) {
                            $presence[$day_active]["clock_in"] = $reset_time;
                        }

                        if (!is_null($item["clock_in"]) && is_null($item["clock_out"])) {
                            $presence[$day_active]["clock_out"] = $reset_time;
                        }

                        if (is_null($item["clock_in"]) && is_null($item["clock_out"])) {
                            $presence[$day_active]["clock_in"] = $reset_time;
                            $presence[$day_active]["clock_out"] = $reset_time;
                            $presence[$day_active]["description"] = "Absent";
                        }
                    }
                }
            }

            $this->param_data["presence_history"] = $presence;
            return view('data/presence_by_user', $this->param_data);
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
