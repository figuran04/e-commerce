<?php
$models = [
    'UserModel' => 'auth',
    'StoreModel' => 'auth',
    'ProductModel' => 'products',
    'CategoryModel' => 'products',
    'SearchModel' => 'products',
    'OrderModel' => 'orders',
    'CartModel' => 'orders'
];

$dir = __DIR__ . '/frontend/models/';

foreach ($models as $model => $service) {
    $code = "<?php\n";
    $code .= "require_once __DIR__ . '/../helpers/service_helper.php';\n\n";
    $code .= "class $model {\n";
    $code .= "    private \$service = '$service';\n";
    $code .= "    private \$model = '$model';\n\n";
    $code .= "    public function __construct(\$db = null) {}\n\n";
    $code .= "    public function __call(\$method, \$args) {\n";
    $code .= "        \$data = ['model' => \$this->model, 'method' => \$method, 'args' => \$args];\n";
    $code .= "        \$res = ServiceHelper::call(\$this->service, 'rpc', 'POST', \$data);\n";
    $code .= "        return (isset(\$res['status']) && \$res['status'] === 'success') ? \$res['data'] : false;\n";
    $code .= "    }\n\n";
    $code .= "    public static function __callStatic(\$method, \$args) {\n";
    $code .= "        \$data = ['model' => '$model', 'method' => \$method, 'args' => \$args];\n";
    $code .= "        \$res = ServiceHelper::call('$service', 'rpc', 'POST', \$data);\n";
    $code .= "        return (isset(\$res['status']) && \$res['status'] === 'success') ? \$res['data'] : false;\n";
    $code .= "    }\n";
    $code .= "}\n";

    file_put_contents($dir . $model . '.php', $code);
    echo "Generated proxy for $model\n";
}
