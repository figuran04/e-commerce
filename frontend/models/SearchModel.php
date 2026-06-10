<?php
require_once __DIR__ . '/../helpers/service_helper.php';

class SearchModel {
    private $service = 'products';
    private $model = 'SearchModel';

    public function __construct($db = null) {}

    public function __call($method, $args) {
        $data = ['model' => $this->model, 'method' => $method, 'args' => $args];
        $res = ServiceHelper::call($this->service, 'rpc', 'POST', $data);
        return (isset($res['status']) && $res['status'] === 'success') ? $res['data'] : false;
    }

    public static function __callStatic($method, $args) {
        $data = ['model' => 'SearchModel', 'method' => $method, 'args' => $args];
        $res = ServiceHelper::call('products', 'rpc', 'POST', $data);
        return (isset($res['status']) && $res['status'] === 'success') ? $res['data'] : false;
    }
}
