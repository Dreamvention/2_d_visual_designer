<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 25;
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
    'title' => 'Browser market shares January, 2015 to May, 2015',
    'stroke_width' => '2',
    'display_legend' => '1',
    'values' => array(
       'value0' => array(
            'label' => 'Microsoft Internet Explorer',
            'value' => '56.33',
            'color' => '#95CEFF'
        ),
        'value1' => array(
            'label' => 'Chrome',
            'value' => '24.03',
            'color' => '#434348'
        ),
        'value2' => array(
            'label' => 'Firefox',
            'value' => '10.38',
            'color' => '#90ED7D'
        ),
        'value3' => array(
            'label' => 'Safari',
            'value' => '4.77',
            'color' => '#F7A35C'
        ),
        'value4' => array(
            'label' => 'Opera',
            'value' => '0.91',
            'color' => '#8085E9'
        ),
        'value5' => array(
            'label' => 'Proprietary or Undetectable',
            'value' => '0.2',
            'color' => '#F58FA7'
        )
    ),
    'mode' => 'pie',
    'animate' => 'easeoutbounce',
    'units' => '',
    'design_margin_bottom' => '15px'
);