<?php

/**
 * Model departement
 * @param  mixed
 * @return array
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Departement extends Model
{
    protected $table = 'app_departement_cd';
    protected $primaryKey = 'departement_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $useSoftDeletes = true;
    protected $allowedFields = ['departement_id', 'departement_cd'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_dttm';
    protected $updatedField  = 'updated_dttm';
    protected $deletedField  = 'nullified_dttm';
}
