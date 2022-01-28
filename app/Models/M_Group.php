<?php

/**
 * Model group
 * @param  mixed
 * @return array
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Group extends Model
{
    protected $table = 'app_group';
    protected $primaryKey = 'group_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['group_id', 'group_cd'];
}
