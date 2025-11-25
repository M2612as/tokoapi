<?php

namespace App\Models;

use CodeIgniter\Model;

class MMember extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'id'; // opsional jika kolomnya id
    protected $allowedFields = ['nama', 'email', 'password'];
}
