<?php
require_once __DIR__ . '/../helpers/service_helper.php';

class StoreModel {
    private $service = 'auth';
    private $model = 'StoreModel';

    public function __construct($db = null) {}

    public function __call($method, $args) {
        $data = ['model' => $this->model, 'method' => $method, 'args' => $args];
        $res = ServiceHelper::call($this->service, 'rpc', 'POST', $data);
        return (isset($res['status']) && $res['status'] === 'success') ? $res['data'] : false;
    }

    public static function __callStatic($method, $args) {
        $data = ['model' => 'StoreModel', 'method' => $method, 'args' => $args];
        $res = ServiceHelper::call('auth', 'rpc', 'POST', $data);
        return (isset($res['status']) && $res['status'] === 'success') ? $res['data'] : false;
    }
}
