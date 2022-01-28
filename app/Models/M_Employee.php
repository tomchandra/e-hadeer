<?php

/**
 * Model employee
 * @param  mixed
 * @return array
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Employee extends Model
{
    protected $table = 'app_persons';
    protected $primaryKey = 'person_ext_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $useSoftDeletes = true;
    protected $allowedFields = ['person_id', 'person_ext_id', 'person_name', 'birth_dttm', 'gender_cd', 'cellphone', 'email', 'address', 'join_dttm', 'departement_id', 'job_id', 'status_cd'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_dttm';
    protected $updatedField  = 'updated_dttm';
    protected $deletedField  = 'nullified_dttm';

    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session  = \Config\Services::session();
        $this->session->start();
    }

    /**
     * Method untuk mengambil semua data karyawan
     * @return array
     */
    function app_getDataListEmployee()
    {
        $sql = "SELECT a.person_ext_id, a.person_name, a.birth_dttm, IF(a.gender_cd = 'f','Female','Male') AS gender_nm, a.cellphone, a.email, a.address, a.join_dttm, b.departement_cd, c.job_cd, a.departement_id, a.job_id, a.gender_cd, d.user_id
                FROM app_persons a 
                LEFT JOIN app_users d USING (person_id)
                LEFT JOIN app_departement_cd b USING (departement_id)
                LEFT JOIN app_job_cd c USING (job_id)
                WHERE a.status_cd = 'normal'
                ORDER BY a.person_name ASC";
        if ($this->db->query($sql)->getNumRows() > 0) {
            return $this->db->query($sql)->getResultArray();
        }
        return [];
    }

    /**
     * Method untuk mencari karyawan
     * @param string person_ext_id
     * @param string person_name
     * @return array
     */
    function app_searchEmployee($string)
    {
        $sql = "SELECT a.person_ext_id, a.person_name, b.departement_cd, c.job_cd, d.user_id
                FROM app_persons a 
                LEFT JOIN app_departement_cd b USING (departement_id)
                LEFT JOIN app_job_cd c USING (job_id)
                LEFT JOIN app_users d USING (person_id)
                WHERE a.status_cd = 'normal' AND (a.person_ext_id LIKE '%${string}%' OR a.person_name LIKE '%${string}%')
                ORDER BY a.person_name ASC";
        $arr["suggestions"] = [];
        if ($this->db->query($sql)->getNumRows() > 0) {
            $result = $this->db->query($sql)->getResultArray();
            foreach ($result as $item) {
                $arr["suggestions"][] = [
                    "value" => $item["person_name"],
                    "data"  => $item["user_id"],
                    "options" => [
                        "ext_id"      => $item["person_ext_id"],
                        "departement" => $item["departement_cd"],
                        "job"         => $item["job_cd"],
                    ],
                ];
            }
        }

        return $arr;
    }

    /**
     * Method untuk mendapat data karyawan
     * @param string person_id
     * @return array
     */
    function app_getEmployee($person_id)
    {
        $sql = "SELECT * FROM app_persons WHERE person_id = '${person_id}'";

        if ($this->db->query($sql)->getNumRows() > 0) {
            return $this->db->query($sql)->getResultArray()[0];
        } else {
            return [];
        }
    }
}
