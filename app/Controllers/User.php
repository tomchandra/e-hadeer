<?php

/**
 * Controller untuk user
 * @param  mixed
 * @return array
 */

namespace App\Controllers;

use App\Models\M_Group;
use App\Models\M_Group_access;
use App\Models\M_Role;
use App\Models\M_Role_access;
use App\Models\M_User;

class User extends BaseController
{
    protected $M_User;
    protected $M_Group;
    protected $M_Role;
    protected $M_Group_access;
    protected $M_Role_access;

    public function __construct()
    {
        $this->M_User         = new M_User();
        $this->M_Group        = new M_Group();
        $this->M_Role         = new M_Role();
        $this->M_Group_access = new M_Group_access();
        $this->M_Role_access  = new M_Role_access();
    }
    public function index()
    {
        $user_id = $this->session->get('user_access_id');

        $this->param_data["list_group"]     = $this->M_Group->orderBy('group_id', 'ASC')->get()->getResultArray();
        $this->param_data["list_role"]      = $this->M_Role->orderBy('role_id', 'ASC')->get()->getResultArray();
        $this->param_data["profile_access"] = $this->M_User->select('user_name, user_status')->where('user_id', $user_id)->get()->getResultArray();
        $this->param_data["group_access"]   = $this->M_Group_access->app_groupAccessMenu($user_id);
        $this->param_data["role_access"]    = $this->M_Role_access->app_roleAccessMenu($user_id);

        //dd($this->param_data);
        return view('app/user', $this->param_data);
    }


    /**
     * Method untuk mencari data pengguna
     * @param int user_id
     * @return array
     */
    public function search()
    {
        if ($this->request->isAJAX()) {
            $user_id = $this->request->getGet('user_id');
            $this->session->set('user_access_id', $user_id);
            echo json_encode(array("message" => "success"));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk manajemen pengguna
     * @param int user_access_id
     * @param string action
     * @param mixed form_data
     * @return array
     */
    public function access()
    {
        if ($this->request->isAJAX()) {
            $user_id = $this->session->get('user_access_id');
            $action  = $this->request->getPost('action');
            $data    = $this->request->getPost('data');

            switch ($action) {
                    // untuk reset akun pengguna
                case "reset_account":
                    try {
                        $this->M_User->app_resetAccount($user_id);
                        echo json_encode(array("message" => "success"));
                    } catch (\Exception $e) {
                        echo json_encode(array("message" => $e->getMessage()));
                    }
                    break;
                    //untuk mengaktifkan kembali akun pengguna
                case "deactive_account":
                    try {
                        $this->M_User->set('user_status', 'active')->where('user_id', $user_id)->update();
                        echo json_encode(array("message" => "success"));
                    } catch (\Exception $e) {
                        echo json_encode(array("message" => $e->getMessage()));
                    }
                    break;
                    // untuk menambah group pengguna
                case "add_group":
                    try {
                        $cek = $this->M_Group_access->select('group_access_menu_id')->where(['group_id' => $data, 'user_id' => $user_id])->first();
                        if (!$cek) {
                            $this->M_Group_access->save(["group_id" => $data, "user_id" => $user_id]);
                        }
                        echo json_encode(array("message" => "success"));
                    } catch (\Exception $e) {
                        echo json_encode(array("message" => $e->getMessage()));
                    }
                    break;
                    //untuk menghapus group pengguna
                case "remove_group":
                    try {
                        foreach ($data as $key) {
                            $arr_data[] = $key['value'];
                        }
                        $this->M_Group_access->whereIn("group_access_menu_id", $arr_data)->delete();
                        echo json_encode(array("message" => "success"));
                    } catch (\Exception $e) {
                        echo json_encode(array("message" => $e->getMessage()));
                    }
                    break;
                    // untuk menambah role pengguna
                case "add_role":
                    try {
                        $cek = $this->M_Role_access->select('role_access_id')->where(['role_id' => $data, 'user_id' => $user_id])->first();
                        if (!$cek) {
                            $this->M_Role_access->save(["role_id" => $data, "user_id" => $user_id]);
                        }
                        echo json_encode(array("message" => "success"));
                    } catch (\Exception $e) {
                        echo json_encode(array("message" => $e->getMessage()));
                    }
                    break;
                    // untuk menghapus role pengguna
                case "remove_role":
                    try {
                        foreach ($data as $key) {
                            $arr_data[] = $key['value'];
                        }
                        $this->M_Role_access->whereIn("role_access_id", $arr_data)->delete();
                        echo json_encode(array("message" => "success"));
                    } catch (\Exception $e) {
                        echo json_encode(array("message" => $e->getMessage()));
                    }
                    break;
                default:
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
