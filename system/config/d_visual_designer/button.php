<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 17;
//Категория(content, social, structure)
$_['category'] = 'content';
//отображать название блока
$_['display_title']   = true;
//Может содержать дочерние блоки
$_['child_blocks']    = false;
//Уровень доступный для добавления блока
$_['level_min']       = 2;
$_['level_max']       = 6;
//Расположение кнопок управления
$_['control_position'] ='popup';
//Отображение кнопок управления
$_['display_control'] = true;
//Кнопка перетаскивания
$_['button_drag']     = true;
//Кнопка редатирования
$_['button_edit']     = true;
//Кнопка копирования
$_['button_copy']     = true ;
//Кнопка сворачивания
$_['button_collapse'] = true;
//Кнопка удаления
$_['button_remove']   = true;
//Настройки по умолчанию
$_['setting'] = array(
    'text' => 'Button',
    'link' => '',
    'new_window' => 0,
    'style' => 'modern',
    'shape' => 'rounded',
    'color' => '#d2d2d2',
    'color_text' => '#000',
    'color_hover' => '#b3b3b3',
    'size' => 'md',
    'alignment' => 'left',
    'full_width' => 0,
    'display_icon' => 0,
    'icon' => 'fa-adjust',
    'animate' => '',
    'design_margin_bottom' => '15px'
);