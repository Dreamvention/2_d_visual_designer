<?php
//Название конфига
$_['name']                = 'Category';
//Статус Frontend редатора
$_['frontend_status']     = '1';
//GET параметр route в админке 
$_['backend_route']       = 'catalog/category/edit';
//REGEX для GET параметров route в админке
$_['backend_route_regex'] = 'catalog/category/*';
//GET параметр route на Frontend 
$_['frontend_route']      = 'product/category';
//GET параметр содержащий id страницы в админке
$_['backend_param']       = 'category_id';
//GET параметр содержащий id страницы на Frontend
$_['frontend_param']      = 'path';
//Путь для сохранения описания на Frontend
$_['edit_route']        = 'extension/d_visual_designer/designer/saveCategory';

//События необходимые для работы данного route
$_['events']              = array(
    'admin/view/catalog/category_form/after' => 'extension/event/d_visual_designer/view_category_after',
    'admin/model/catalog/category/addCategory/before' => 'extension/event/d_visual_designer/model_catalog_category_addCategory_before',
    'admin/model/catalog/category/addCategory/after' => 'extension/event/d_visual_designer/model_catalog_category_addCategory_after',
    'admin/model/catalog/category/editCategory/before' => 'extension/event/d_visual_designer/model_catalog_category_editCategory_before',
    'admin/model/catalog/category/editCategory/after' => 'extension/event/d_visual_designer/model_catalog_category_editCategory_after',
    'catalog/view/product/category/before' => 'extension/event/d_visual_designer/view_category_before'
);