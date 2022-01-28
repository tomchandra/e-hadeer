<?php

/**
 * Model role access
 * @param mix
 * @return mix
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Role_access extends Model
{
    protected $table = 'app_role_access';
    protected $primaryKey = 'role_access_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['role_access_id', 'role_id', 'user_id'];

    /**
     * Method untuk mencari role access berdasarkan id pengguna
     * @param int user_id
     * @return array
     */
    function app_roleAccessMenu($user_id)
    {
        $sql = "SELECT a.role_access_id, b.role_cd
                FROM app_role_access a 
                LEFT JOIN app_role b USING(role_id)
                WHERE a.user_id = '${user_id}'";

        if ($this->db->query($sql)->getNumRows() > 0) {
            return $this->db->query($sql)->getResultArray();
        } else {
            return [];
        }
    }
}
