<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\SecretModel;
use PhpParser\Node\Stmt\TryCatch;

class Secret extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
    */
    protected $secret_model = null;
    function __construct()
    {
        $this->secret_model = new SecretModel();
    }

    public function index()
    {
        $model = new SecretModel();
        $data = $model->findAll();
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
    */

    public function show($result = null)
    {
        $model = new SecretModel();
        $secret_key = $this->secret_model->getIdbySecretkey($result);
        $data = $model->find(['id' => $secret_key]);
        if (!$data) return $this->failNotFound('No Data Found');
        // echo json_encode($secret_data);
        // die('###');
        return $this->respond($data);
    }

    public function create()
    {
        helper(['form']);
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $data = [
            'secret_key' => substr(str_shuffle($str_result),0,30),
            'secret_data' => $this->request->getVar('secret_data'),
            'secret_url' => 'http://localhost:3000/secret/',
            'secret_timedue' => $this->request->getVar('secret_timedue'),
            'passphrase' => $this->request->getVar('passphrase'),
            'status' => 1,
        ];

        try {
        $result = $this->secret_model->create($data);
        $secret_key = $this->secret_model->getSecretKey($result);
        $this->show($result);
        }catch (\Exception $e) {
            die($e->getMessage());
        }
        if ($result) {
            $response = [
                'status' => 201,
                'error' => null,
                'message' => [
                    'success' => 'Data Inserted',
                    'inserted_data' => $secret_key
                ]
            ];
        } else {
            $response = [
                'status' => 400,
                'error' => 'Data Not Inserted',
                'message' => [
                    'error' => $result
                ]
            ];
        }
        echo json_encode($response);
        die();
    }

    public function update($id = null)
    {
        helper(['form']);
        $rules = [
            'secret_data' => 'required',
        ];

        $data = [
            'secret_key' => rand(5,10),
            'secret_data' => $this->request->getVar('secret_data'),
            'secret_url' => $this->request->getVar('secret_url'),
            'secret_timedue' => $this->request->getVar('secret_timedue'),
            'passphrase' => $this->request->getVar('passphrase'),
            'status' => 1,
        ];

        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model = new SecretModel();
        $model->update($id, $data);
        $response = [
            'status' => 201,
            'error' => null,
            'message' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */

    public function delete($id = null)
    {
        $model = new SecretModel();
        $secret_key = $this->secret_model->getIdbySecretkey($id);
        $findById = $model->find(['id' => $secret_key]);
        if (!$findById) return $this->failNotFound('No Data Found');
        $model->delete($secret_key);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => [
                'success' => 'Data Deleted'
            ]
        ];
        return $this->respond($response);
    }
}
