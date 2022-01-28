<?php

/**
 * Model role
 * @param mix
 * @return mix
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Role extends Model
{
    protected $table = 'app_role';
    protected $primaryKey = 'role_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['role_id', 'group_cd'];
}
