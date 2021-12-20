<?php

namespace App\Models;
use CodeIgniter\Model;
class SecretModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'secrets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['secret_key','secret_data','secret_url','secret_timedue','passphrase','status'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true;
    protected $cleanValidationRules = false;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function create($data= null) {
        try {
            $this->save($data);
            $secret_id = $this->getInsertID();
            return $secret_id;
        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }

    public function getSecretById($id = null){
        try {
           $data = $this->find(['id' => $id]);
           return $data;
        } catch (\Exception $e) {
           die($e->getMessage());
        }
    }

    public function getSecretKey($result = null){
        try {
            $secret_data = $this->where('id', $result)
            ->findColumn('secret_key');
            return $secret_data[0];
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getIdbySecretkey($secret = null){
        try {
            $id_data = $this->where('secret_key', $secret)
                ->findColumn('id');
            return $id_data[0];
        } catch (\Throwable $th) {
            //throw $th;
        }
    }



}

