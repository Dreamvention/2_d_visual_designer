<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 12;
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
    'images' => array(),
    'title' => '',
    'size' => 'original',
    'onclick' => '',
    'link' => '',
    'link_target' => 'new',
    'speed' => '5000',
    'slides_per_view' => 1,
    'auto_play' => 0,
    'hide_pagination_control' => 0,
    'hide_next_prev_button' => 0,
    'stopOnHover' => 0,
    'lazyLoad' => 0,
    'animate' => '',
    'design_margin_bottom' => '15px',
    'width' => 0,
    'height' => 0
);