<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class RestfulController extends ResourceController
{
    protected $format = 'json';

    // CONSTRUCTOR boleh dikosongkan
    public function __construct()
    {
        // Tidak perlu set $this->request
    }

    protected function responseHasil($code, $status, $data)
    {
        return $this->respond([
            'code'   => $code,
            'status' => $status,
            'data'   => $data
        ]);
    }
}
