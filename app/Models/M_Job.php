<?php

/**
 * Model job
 * @param  mixed
 * @return array
 */

namespace App\Models;

use CodeIgniter\Model;

class M_Job extends Model
{
    protected $table = 'app_job_cd';
    protected $primaryKey = 'job_id';

    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    protected $useSoftDeletes = true;
    protected $allowedFields = ['job_id', 'job_cd'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_dttm';
    protected $updatedField  = 'updated_dttm';
    protected $deletedField  = 'nullified_dttm';
}
