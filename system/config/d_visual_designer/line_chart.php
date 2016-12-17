<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 26;
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
    'title' => 'Monthly Average Temperature',
    'design' => 'pie',
    'stroke_width' => '2',
    'display_legend' => '1',
    'x_values' => 'Jan; Feb; Mar; Apr; May; Jun; Jul; Aug; Sep; Oct; Nov; Dec',
    'values' => array(
        'value0' => array(
            'label' => 'Tokyo',
            'value' => '7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6',
            'color' => '#7CB5EC'
        ),
        'value1' => array(
            'label' => 'New York',
            'value' => '-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5',
            'color' => '#434348'
        ),
        'value2' => array(
            'label' => 'Berlin',
            'value' => '-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0',
            'color' => '#90ED7D'
        ),
        'value3' => array(
            'label' => 'London',
            'value' => '3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8',
            'color' => '#F7A35C'
        ),
    ),
    'mode' => 'line',
    'animate' => 'easeoutbounce',
    'design_margin_bottom' => '15px'
);