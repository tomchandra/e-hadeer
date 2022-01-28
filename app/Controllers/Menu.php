<?php

/**
 * Controller untuk menu
 * @param  mixed
 * @return array
 */

namespace App\Controllers;

class Menu extends BaseController
{
    public function index()
    {
        $this->param_data["data_menu"] = $this->M_Menu->app_getAllMenu();
        return view('app/menu', $this->param_data);
    }

    /**
     * Method untuk menampilkan detail data menu
     * @param int menu_id
     * @return array
     */
    public function detail()
    {
        if ($this->request->isAJAX()) {
            $menu_id = $this->request->getGet('menu_id');
            try {
                $result  = $this->M_Menu->where('menu_id', $menu_id)->get()->getResultArray();
                echo json_encode(array("message" => "success", "data" => $result[0]));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }


    /**
     * Method untuk menambah data menu
     * @param string form_data
     * @return array
     */
    public function add()
    {
        if ($this->request->isAJAX()) {
            $arr_data = array();
            $data = $this->request->getPost('data');
            foreach ($data as $key) {
                $arr_data[$key['name']] = $key['value'];
            }

            try {
                $this->M_Menu->insert($arr_data);
                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Method untuk merubah data menu
     * @param string form_data
     * @return array
     */
    public function update()
    {
        if ($this->request->isAJAX()) {
            $arr_data = array();
            $menu_id  = $this->request->getPost('menu_id');
            $data     = $this->request->getPost('data');
            foreach ($data as $key) {
                $arr_data[$key['name']] = $key['value'];
            }

            try {
                $this->M_Menu->update($menu_id, $arr_data);
                echo json_encode(array("message" => "success"));
            } catch (\Exception $e) {
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
