<?php

/**
 * Controller untuk group
 * @param  mixed
 * @return array
 */

namespace App\Controllers;

use App\Models\M_Group;
use App\Models\M_Group_access;
use App\Models\M_Group_menu;
use App\Models\M_Role;
use App\Models\M_Role_access;
use App\Models\M_User;

class Group extends BaseController
{
    protected $M_User;
    protected $M_Group;
    protected $M_Role;
    protected $M_Group_access;
    protected $M_Role_access;
    protected $M_Group_menu;

    public function __construct()
    {
        $this->M_User         = new M_User();
        $this->M_Group        = new M_Group();
        $this->M_Role         = new M_Role();
        $this->M_Group_access = new M_Group_access();
        $this->M_Role_access  = new M_Role_access();
        $this->M_Group_menu   = new M_Group_menu();
    }
    public function index()
    {
        $this->param_data["data_menu"] = $this->M_Menu->app_getAllMenu();
        $this->param_data["group"]     = $this->M_Group->get()->getResultArray();

        return view('app/group', $this->param_data);
    }

    /**
     * Method untuk menampilkan detail data group access
     * @param int group_id
     * @return array
     */
    public function detail_access()
    {
        if ($this->request->isAJAX()) {
            $group_id = $this->request->getGet('group_id');
            try {
                $result  = $this->M_Group_access->app_groupAccessMenuByGroupId($group_id);
                echo json_encode(array("message" => "success", "data" => $result));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }


    /**
     * Method untuk menambah group
     * @param mixed form_data
     * @return array
     */
    public function add()
    {
        if ($this->request->isAJAX()) {
            $group_name = $this->request->getPost('data');
            try {
                $result  = $this->M_Group->insert(["group_cd" => $group_name]);
                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk menambah group access
     * @param mixed form_data
     * @return array
     */
    public function add_access()
    {
        if ($this->request->isAJAX()) {
            $arr_data_insert = array();
            $group_id = $this->request->getPost('group_id');
            $data     = $this->request->getPost('data');
            foreach ($data as $key) {
                $arr_data_insert[] = [
                    "group_id" => $group_id,
                    "menu_id"  => $key["value"],
                ];
            }

            try {
                $this->M_Group_menu->where('group_id', $group_id)->delete();
                $this->M_Group_menu->insertBatch($arr_data_insert);
                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
