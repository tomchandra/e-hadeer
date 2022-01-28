<?php

/**
 * Controller untuk employee
 * @param  mixed
 * @return array
 */

namespace App\Controllers;

use App\Models\M_Departement;
use App\Models\M_Employee;
use App\Models\M_Job;
use App\Models\M_User;

class Employee extends BaseController
{
    protected $M_Employee;
    protected $M_Departement;
    protected $M_Job;
    protected $M_User;

    public function __construct()
    {
        $this->M_Employee = new M_Employee();
        $this->M_Departement = new M_Departement();
        $this->M_Job = new M_Job();
        $this->M_User = new M_User();
    }

    public function index()
    {
        $this->param_data["departement"] = $this->M_Departement->select('departement_id, departement_cd')->orderBy('departement_cd', 'ASC')->get()->getResultArray();
        $this->param_data["job"] = $this->M_Job->select('job_id, job_cd')->orderBy('job_cd', 'ASC')->get()->getResultArray();

        return view('app/employee', $this->param_data);
    }

    /**
     * Method untuk menampilkan semua data employee
     * @return array
     */
    public function list()
    {
        if ($this->request->isAJAX()) {
            $data = $this->M_Employee->app_getDataListEmployee();

            echo json_encode(array("data" => $data));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk menambah data employee
     * @param mixed form_data
     * @return array
     */
    public function add()
    {
        if ($this->request->isAJAX()) {
            $arr_data = array();
            $data = $this->request->getPost('data');
            foreach ($data as $key) {
                $arr_data[$key['name']] = $key['value'];

                if ($key['name'] == "person_ext_id") {
                    $arr_data_user['user_name'] = $key['value'];
                }

                if ($key['name'] == "birth_dttm") {
                    $arr_data_user['password'] = md5($key['value']);
                }
            }

            $count = $this->M_Employee->where('person_ext_id', $arr_data['person_ext_id'])->countAllResults();
            if ($count < 1) {
                try {
                    $this->M_Employee->insert($arr_data);

                    $arr_data_user['person_id'] = $this->M_Employee->getInsertID();
                    $this->M_User->insert($arr_data_user);

                    echo json_encode(array("message" => "success"));
                } catch (\Exception $e) {
                    echo json_encode(array("message" => $e->getMessage()));
                }
            } else {
                echo json_encode(array("message" => "duplicate"));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk merubah data employee
     * @param mixed form_data
     * @return array
     */
    public function update()
    {
        if ($this->request->isAJAX()) {
            $arr_data = array();
            $data = $this->request->getPost('data');
            foreach ($data as $key) {
                $arr_data[$key['name']] = $key['value'];
            }

            try {
                $this->M_Employee->save($arr_data);
                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk menghapus data employee
     * @param mixed form_data
     * @return array
     */
    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            try {
                $data = [
                    "person_ext_id" => $id,
                    "status_cd" => "nullified",
                    "nullified_user_id" => "",
                    "nullified_dttm" => date("Y-m-d H:i:s"),

                ];

                $this->M_Employee->save($data);

                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk mencari data employee
     * @param string search
     * @return array
     */
    public function search()
    {
        if ($this->request->isAJAX()) {
            $search_string = $this->request->getGet('search_string');
            echo json_encode($this->M_Employee->app_searchEmployee($search_string));
        }
    }
}
