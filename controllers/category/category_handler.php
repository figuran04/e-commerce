<?php
require_once '../../models/CategoryModel.php';

$categoryModel = new CategoryModel($conn);
$categories = $categoryModel->getAll();
