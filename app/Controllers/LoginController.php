<?php

namespace App\Controllers;

use App\Models\MLogin;
use App\Models\MMember;
use App\Controllers\RestfulController;

class LoginController extends RestfulController
{
    public function login()
    {
        // Baca data dari berbagai sumber: JSON body, POST, atau GET
        $email = $password = null;

        // Cek apakah body adalah JSON
        $contentType = $this->request->getHeaderLine('Content-Type');
        if (stripos($contentType, 'application/json') !== false) {
            // Parse JSON dari raw body
            $raw = $this->request->getBody();
            $json = json_decode($raw, true);
            if (is_array($json)) {
                $email = $json['email'] ?? null;
                $password = $json['password'] ?? null;
            }
        }

        // Fallback ke $_POST atau query string
        if (empty($email)) {
            $email = $_POST['email'] ?? $_GET['email'] ?? null;
        }
        if (empty($password)) {
            $password = $_POST['password'] ?? $_GET['password'] ?? null;
        }

        // Validasi input
        if (empty($email) || empty($password)) {
            return $this->responseHasil(400, false, "Email dan password wajib diisi");
        }

        // Cari user berdasarkan email
        $model = new MMember();
        $member = $model->where('email', $email)->first();

        if (!$member) {
            return $this->responseHasil(400, false, "Email tidak ditemukan");
        }

        // Cek password
        if (!password_verify($password, $member['password'])) {
            return $this->responseHasil(400, false, "Password tidak valid");
        }

        // Buat token login
        $auth_key = $this->RandomString(60);

        $login = new MLogin();
        $login->save([
            'member_id' => $member['id'],
            'auth_key'  => $auth_key
        ]);

        // Data response
        $data = [
            'token' => $auth_key,
            'user' => [
                'id'    => $member['id'],
                'email' => $member['email']
            ]
        ];

        return $this->responseHasil(200, true, $data);
    }

    private function RandomString($length = 60)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($characters) - 1;
        $str = '';

        for ($i = 0; $i < $length; $i++) {
            $str .= $characters[random_int(0, $max)];
        }

        return $str;
    }
}