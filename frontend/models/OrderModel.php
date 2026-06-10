<?php
require_once __DIR__ . '/../helpers/service_helper.php';

class OrderModel {
    private $service = 'orders';
    private $model = 'OrderModel';

    public function __construct($db = null) {}

    public function __call($method, $args) {
        $data = ['model' => $this->model, 'method' => $method, 'args' => $args];
        $res = ServiceHelper::call($this->service, 'rpc', 'POST', $data);
        return (isset($res['status']) && $res['status'] === 'success') ? $res['data'] : false;
    }

    public static function __callStatic($method, $args) {
        $data = ['model' => 'OrderModel', 'method' => $method, 'args' => $args];
        $res = ServiceHelper::call('orders', 'rpc', 'POST', $data);
        return (isset($res['status']) && $res['status'] === 'success') ? $res['data'] : false;
    }
}
