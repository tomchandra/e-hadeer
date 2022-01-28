<?php

/**
 * Model menu
 * @param  mixed
 * @return array
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Menu extends Model
{

	protected $table = 'app_menu_cd';
	protected $primaryKey = 'menu_id';

	protected $returnType     = 'array';
	protected $useAutoIncrement = true;

	protected $allowedFields = ['menu_id', 'menu_parent_id', 'menu_cd', 'menu_url', 'menu_order', 'status_cd'];

	protected $db;
	protected $session;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->session  = \Config\Services::session();
		$this->session->start();
	}

	/**
	 * Method untuk mengambil semua data menu
	 * @return array
	 */
	function app_getAllMenu()
	{
		$arr_menu_parent = array();
		$arr_menu_child = [];

		$sql = "SELECT menu_id, menu_parent_id, menu_cd, menu_url, menu_order
				FROM app_menu_cd
				WHERE status_cd = 'active'
				ORDER BY menu_parent_id, menu_order ASC";

		if ($this->db->query($sql)->getNumRows() > 0) {
			$result = $this->db->query($sql)->getResultArray();
			foreach ($result as $item) {
				$arr_eligible_menu[] = $item['menu_url'];
				if ($item['menu_parent_id'] == "0") {
					$arr_menu_parent[$item['menu_id']] = array($item['menu_parent_id'], $item['menu_id'], $item['menu_cd'], $item['menu_url']);
				} else {
					$arr_menu_child[$item['menu_parent_id']][] = array($item['menu_parent_id'], $item['menu_id'], $item['menu_cd'], $item['menu_url']);
				}
			}

			foreach ($arr_menu_parent as $parent) {
				$menu_child = [];

				if (array_key_exists($parent['1'], $arr_menu_child)) {
					foreach ($arr_menu_child[$parent['1']] as $child) {
						$arr_child[$child['0']][] = [
							"menu_id"   => $child['1'],
							"menu_name" => $child['2'],
							"menu_url"  => $child['3'],
						];
					}

					$menu_child = $arr_child[$parent['1']];
				}



				$arr_menu[] = [
					"menu_id"     => $parent['1'],
					"menu_name"   => $parent['2'],
					"menu_url"    => $parent['3'],
					"menu_child"  => $menu_child,
				];
			}
			return $arr_menu;
		} else {
			return [];
		}
	}

	/**
	 * Method untuk mencari menu berdasarkan id pengguna
	 * @param int user_id
	 * @return array
	 */
	function app_getDataListMenu($user_id)
	{
		$arr_menu_parent = array();
		$arr_menu_child = [];

		$sql = "SELECT b.menu_id, c.menu_parent_id, c.menu_cd, c.menu_url, c.menu_order
                FROM app_group_access a 
                LEFT JOIN app_group_menu b USING(group_id)
                LEFT JOIN app_menu_cd c USING(menu_id)
                WHERE a.user_id = '${user_id}' AND c.status_cd = 'active'
                ORDER BY c.menu_parent_id, c.menu_order ASC";

		if ($this->db->query($sql)->getNumRows() > 0) {
			$result = $this->db->query($sql)->getResultArray();
			foreach ($result as $item) {
				$arr_eligible_menu[] = explode("/", $item['menu_url'])[0];
				if ($item['menu_parent_id'] == "0") {
					$arr_menu_parent[$item['menu_id']] = array($item['menu_parent_id'], $item['menu_id'], $item['menu_cd'], $item['menu_url']);
				} else {
					$arr_menu_child[$item['menu_parent_id']][] = array($item['menu_parent_id'], $item['menu_id'], $item['menu_cd'], $item['menu_url']);
				}
			}

			foreach ($arr_menu_parent as $parent) {
				$menu_child = [];
				if (array_key_exists($parent['1'], $arr_menu_child)) {
					foreach ($arr_menu_child[$parent['1']] as $child) {
						$arr_child[$child['0']][] = [
							"menu_id"   => $child['1'],
							"menu_name" => $child['2'],
							"menu_url"  => $child['3'],
						];
					}

					$menu_child = $arr_child[$parent['1']];
				}

				$arr_menu[] = [
					"menu_id"     => $parent['1'],
					"menu_name"   => $parent['2'],
					"menu_url"    => $parent['3'],
					"menu_child"  => $menu_child,
				];
			}
			$this->session->set("eligible_access", $arr_eligible_menu);
			return $arr_menu;
		} else {
			$this->session->set("eligible_access", []);
			return [];
		}
	}
}
