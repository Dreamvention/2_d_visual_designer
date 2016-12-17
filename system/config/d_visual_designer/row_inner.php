<?php
//отображение блока в окне при выборе блока
$_['display']          = false;
//Категория(content, social, structure)
$_['category'] = 'content';
//отображать название блока
$_['display_title']    = false;

$_['display_level_0']  = true;
//Может содержать дочерние блоки
$_['child_blocks']     = true;
//Обязательынй дочерний блок
$_['child']            = 'column_inner';
//Уровень доступный для добавления блока
$_['level_min']        = 2;
$_['level_max']        = 2;
//Расположение кнопок управления
$_['control_position'] = 'advanced';

//Отображение кнопок управления
$_['display_control'] = true;
//Кнопка перетаскивания
$_['button_drag']      = true;
//Кнопка редатирования
$_['button_edit']      = true;
//Кнопка копирования
$_['button_copy']      = true ;
//Кнопка сворачивания
$_['button_collapse']  = true;
//Кнопка удаления
$_['button_remove']    = true;
//Настройки по умолчанию
$_['setting']          = array(
    'design_margin_bottom' => '15px'
);