<?php
//Название конфига
$_['name']              = 'Information page';
//Статус Frontend редатора
$_['frontend_status']   = '1';
//GET параметр route в админке 
$_['backend_route']     = 'catalog/information/edit';
//REGEX для GET параметров route в админке
$_['backend_route_regex'] = 'catalog/information/*';
//GET параметр route на Frontend
$_['frontend_route']    = 'information/information';
//GET параметр содержащий id страницы в админке
$_['backend_param']     = 'information_id';
//GET параметр содержащий id страницы на Frontend
$_['frontend_param']    = 'information_id';
//Путь для сохранения описания на Frontend
$_['edit_route']        = 'extension/d_visual_designer/designer/saveInformation';
//События необходимые для работы данного route
$_['events']            = array(
    'admin/view/catalog/information_form/after' => 'extension/event/d_visual_designer/view_information_after',
    'admin/model/catalog/information/addInformation/after' => 'extension/event/d_visual_designer/model_catalog_infromation_addInformation_after',
    'admin/model/catalog/information/addInformation/before' => 'extension/event/d_visual_designer/model_catalog_infromation_addInformation_before',
    'admin/model/catalog/information/editInformation/after' => 'extension/event/d_visual_designer/model_catalog_infromation_editInformation_after',
    'admin/model/catalog/information/editInformation/before' => 'extension/event/d_visual_designer/model_catalog_infromation_editInformation_before',
    'catalog/view/information/information/before' => 'extension/event/d_visual_designer/view_information_before'
);