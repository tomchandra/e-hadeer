<?php

/**
 * Model group access
 * @param  mixed
 * @return array
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Group_access extends Model
{
    protected $table = 'app_group_access';
    protected $primaryKey = 'group_access_menu_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['group_access_menu_id', 'group_id', 'user_id'];

    /**
     * Method untuk mencari data group access
     * @param int user_id
     * @return array
     */
    function app_groupAccessMenu($user_id)
    {
        $sql = "SELECT a.group_access_menu_id, b.group_cd
                FROM app_group_access a 
                LEFT JOIN app_group b USING(group_id)
                WHERE a.user_id = '${user_id}'";

        if ($this->db->query($sql)->getNumRows() > 0) {
            return $this->db->query($sql)->getResultArray();
        } else {
            return [];
        }
    }

    /**
     * Method untuk mencari data group access
     * @param int group_id
     * @return array
     */
    function app_groupAccessMenuByGroupId($group_id)
    {
        $sql = "SELECT menu_id
                FROM app_group_menu
                WHERE group_id = '${group_id}'
                ORDER BY menu_id ASC";

        if ($this->db->query($sql)->getNumRows() > 0) {
            return $this->db->query($sql)->getResultArray();
        } else {
            return [];
        }
    }
}
