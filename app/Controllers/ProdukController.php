<?php

namespace App\Controllers;

use App\Models\MProduk;
use App\Controllers\RestfulController;

class ProdukController extends RestfulController
{
    // Fungsi untuk baca input dari JSON / POST / GET (mirip login)
    private function getInputData()
    {
        $data = [];

        // Cek apakah body JSON
        $contentType = $this->request->getHeaderLine('Content-Type');
        if (stripos($contentType, 'application/json') !== false) {
            $raw = $this->request->getBody();
            $json = json_decode($raw, true);
            if (is_array($json)) {
                $data = $json;
            }
        }

        // Fallback POST
        if (empty($data)) {
            $data = $_POST;
        }

        // Fallback GET
        if (empty($data)) {
            $data = $_GET;
        }

        return $data;
    }

    // CREATE PRODUK
    public function create()
    {
        $input = $this->getInputData();

        $kode_produk = $input['kode_produk'] ?? null;
        $nama_produk = $input['nama_produk'] ?? null;
        $harga       = $input['harga'] ?? null;

        if (!$kode_produk || !$nama_produk || !$harga) {
            return $this->responseHasil(400, false, "Semua field wajib diisi");
        }

        $model = new MProduk();
        $model->insert([
            'kode_produk' => $kode_produk,
            'nama_produk' => $nama_produk,
            'harga'       => $harga
        ]);

        $produk = $model->find($model->getInsertID());

        return $this->responseHasil(200, true, $produk);
    }

    // LIST PRODUK
    public function list()
    {
        $model = new MProduk();
        $produk = $model->findAll();
        return $this->responseHasil(200, true, $produk);
    }

    // DETAIL PRODUK
    public function detail($id)
    {
        $model = new MProduk();
        $produk = $model->find($id);
        return $this->responseHasil(200, true, $produk);
    }

    // UPDATE PRODUK
    public function ubah($id)
    {
        $input = $this->getInputData();

        $kode_produk = $input['kode_produk'] ?? null;
        $nama_produk = $input['nama_produk'] ?? null;
        $harga       = $input['harga'] ?? null;

        if (!$kode_produk || !$nama_produk || !$harga) {
            return $this->responseHasil(400, false, "Semua field wajib diisi");
        }

        $model = new MProduk();
        $model->update($id, [
            'kode_produk' => $kode_produk,
            'nama_produk' => $nama_produk,
            'harga'       => $harga
        ]);

        $produk = $model->find($id);

        return $this->responseHasil(200, true, $produk);
    }

    // DELETE PRODUK
    public function hapus($id)
    {
        $model = new MProduk();
        $model->delete($id);
        return $this->responseHasil(200, true, "Produk berhasil dihapus");
    }
}
