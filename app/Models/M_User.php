<?php

/**
 * Model user
 * @param mix
 * @return mix
 */

namespace App\Models;

use CodeIgniter\Model;

class M_User extends Model
{
    protected $table = 'app_users';
    protected $allowedFields = ['user_id', 'user_name', 'password', 'user_status', 'person_id', 'last_login_dttm'];


    /**
     * Method untuk mereset akun pengguna
     * @param int user_id
     * @return array
     */
    function app_resetAccount($user_id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $sql = "SELECT b.person_ext_id, b.birth_dttm
                FROM app_users a 
                LEFT JOIN app_persons b USING (person_id)
                WHERE a.user_id = '${user_id}'";
        if ($db->query($sql)->getNumRows() > 0) {
            $data = $db->query($sql)->getResultArray()[0];

            $builder->set('user_name', $data['person_ext_id']);
            $builder->set('password', md5($data['birth_dttm']));
            $builder->where('user_id', $user_id);
            $builder->update();
        }
    }
}
