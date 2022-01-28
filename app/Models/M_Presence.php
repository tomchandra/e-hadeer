<?php

/**
 * Model Presence
 * @param mix
 * @return mix
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Presence extends Model
{
    protected $table = 'app_presence';
    protected $primaryKey = 'presence_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['presence_id', 'presence_type', 'presence_dttm', 'user_id', 'presence_image'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_dttm';
    protected $updatedField  = 'updated_dttm';

    /**
     * Method untuk mengambil histori presensi berdasarkan user_id dan bulan
     * @param int user_id
     * @param date date
     * @return array
     */
    function app_getCurrentMonthYearPresenceByUser($user_id, $date)
    {
        $first_date_of_month = date('Y-m-01 00:00:00', strtotime($date));
        $end_date_of_month   = date('Y-m-t 23:59:59', strtotime($date));

        $sql = "SELECT user_id, presence_type, MAX(presence_dttm) AS presence_dttm
                FROM app_presence
                WHERE presence_dttm >= '${first_date_of_month}' AND presence_dttm <= '${end_date_of_month}' AND user_id = '$user_id'
                GROUP BY user_id, presence_type, DATE(presence_dttm)
                ORDER BY presence_dttm";

        if ($this->db->query($sql)->getNumRows() > 0) {
            return $this->db->query($sql)->getResultArray();
        } else {
            return [];
        }
    }


    /**
     * Method untuk mengambil histori presensi berdasarkan range tanggal
     * @param int user_id 
     * @param int date
     * @return array
     */
    function app_getPresenceByRangeDate($start_date, $end_date)
    {
        //$sql = "CALL getDataAbsen('${start_date}','$end_date')";
        $sql = "SELECT user_id, DATE(MAX(presence_dttm)) AS working_days,
                MAX(IF(presence_type = 'clock_in', TIME(presence_dttm), NULL)) AS clock_in,
                MAX(IF(presence_type = 'clock_out', TIME(presence_dttm), NULL)) AS clock_out
                FROM app_presence WHERE DATE(presence_dttm) >= '${start_date}' AND DATE(presence_dttm) <= '${end_date}'
                GROUP BY DATE(presence_dttm), user_id";
        if ($this->db->query($sql)->getNumRows() > 0) {
            return $this->db->query($sql)->getResultArray();
        } else {
            return [];
        }
    }
}
