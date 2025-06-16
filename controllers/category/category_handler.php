<?php
require_once '../../models/CategoryModel.php';

$categoryModel = new CategoryModel($conn);
$categories = $categoryModel->getAll(); // langsung fetchAll(PDO::FETCH_ASSOC)

$hasCategories = !empty($categories);
if (!$hasCategories) return;

// Susun pohon
function buildTree(array $elements, $parentId = 0)
{
  $branch = [];
  foreach ($elements as $element) {
    if ((int)$element['parent_id'] === (int)$parentId) {
      $children = buildTree($elements, $element['id']);
      if ($children) {
        $element['children'] = $children;
      }
      $branch[] = $element;
    }
  }
  return $branch;
}

$categoryTree = buildTree($categories);
$rootCategories = array_filter($categories, fn($c) => (int)$c['parent_id'] === 0);

// ⛳ Default: jika tidak ada query `category`, ambil kategori root pertama secara alfabet
$defaultRootCategoryId = reset($rootCategories)['id'] ?? null;
$selectedCategoryId = isset($_GET['category']) ? (int)$_GET['category'] : $defaultRootCategoryId;

// Ambil branch dari kategori yang dipilih
function findCategoryBranch($categories, $targetId)
{
  foreach ($categories as $category) {
    if ((int)$category['id'] === $targetId) {
      return $category;
    }
    if (!empty($category['children'])) {
      $found = findCategoryBranch($category['children'], $targetId);
      if ($found) return $found;
    }
  }
  return null;
}

$selectedCategory = $selectedCategoryId ? findCategoryBranch($categoryTree, $selectedCategoryId) : null;
