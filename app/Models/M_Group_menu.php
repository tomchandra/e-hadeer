<?php

/**
 * Model group menu
 * @param  mixed
 * @return array
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Group_menu extends Model
{
    protected $table = 'app_group_menu';
    protected $primaryKey = 'group_menu_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['group_menu_id', 'group_id', 'menu_id'];
}
