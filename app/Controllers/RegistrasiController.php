<?php

namespace App\Controllers;

use App\Models\MRegistrasi;
use CodeIgniter\HTTP\IncomingRequest;

class RegistrasiController extends RestfulController
{
    /**
     * @var IncomingRequest
     */
    protected $request;

    public function registrasi()
    {
        $nama = $this->request->getVar('nama');
        $email = $this->request->getVar('email');
        $passwordRaw = $this->request->getVar('password');

        // Validasi singkat: cek field wajib
        if (empty($nama) || empty($email) || empty($passwordRaw)) {
            return $this->responseHasil(400, false, 'Field "nama", "email", dan "password" wajib diisi.');
        }

        $data = [
            'nama'     => $nama,
            'email'    => $email,
            'password' => password_hash($passwordRaw, PASSWORD_DEFAULT)
        ];

        $model = new MRegistrasi();
        $model->save($data);

        return $this->responseHasil(200, true, "Registrasi Berhasil");
    }
}