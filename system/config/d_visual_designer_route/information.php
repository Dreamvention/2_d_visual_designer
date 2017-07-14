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
$_['edit_url']          = 'index.php?route=extension/d_visual_designer/designer/saveInformation';
//События необходимые для работы данного route
$_['events']            = array(
    'admin/view/catalog/information_form/after' => 'extension/event/d_visual_designer/view_information_after',
    'catalog/model/catalog/information/getInformation/after' => 'extension/event/d_visual_designer/model_getInformation_after'
);