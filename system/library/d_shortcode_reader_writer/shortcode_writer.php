<?php

namespace d_shortcode_reader_writer;

class shortcode_writer
{
    private $setting = array();

    public function writeShortcode($setting)
    {
        $this->setting = $setting;
        
        return $this->getContent();
    }

    protected function getContent($parent = '')
    {
        $content = '';
        $items = $this->getItemsByParent($parent);
        
        if (!empty($items)) {
            foreach ($items as $item) {
                $subItems = $this->getItemsByParent($item['id']);
                if (!empty($subItems)) {
                    $childContent = $this->getContent($item['id']);
                    $content .= $this->getShortcode($item, $childContent);
                } else {
                    $content .= $this->getContent($item['id']);
                }
            }
        } else {
            $content = $this->getShortcode($this->setting[$parent]);
        }
        return $content;
    }

    protected function getShortcode($item, $content = '')
    {
        $result = '[vd_'.$item['type'];
        $result .= ' '.$this->writeAttr($item['setting']).' ';
        if (!empty($content)) {
            $result .= ']'.$content.'[/vd_'.$item['type'].']';
        } else {
            $result .= '/]';
        }

        return $result;
    }

    protected function writeAttr($setting)
    {
        $result = '';

        foreach ($setting as $name => $value) {
            if (is_array($value)) {
                $values = $this->writeAttrArray($name, $value);
                foreach ($values as $fullName => $value2) {
                    $fullName = preg_replace('/\]\[/', ':', $fullName);
                    $fullName = preg_replace('/\[/', '::', $fullName);
                    $fullName = preg_replace('/\]/', '', $fullName);
                    $result .= ' ' . $fullName . '=\'' . $this->escape($value2) . '\'' . ' ';
                }
            } else {
                $result .= ' ' . $name . '=\'' . $this->escape($value) . '\'' . ' ';
            }
        }

        return $result;
    }

    protected function writeAttrArray($name, $value)
    {
        $this->attr = array();
        foreach ($value as $key => $item) {
            $fullName = $name.'['.$key.']';
            $this->writeAttrArrayHelper($fullName, $item);
        }
        return $this->attr;
    }

    protected function writeAttrArrayHelper($name, $value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $fullName = $name.'['.$key.']';
                $this->writeAttrArrayHelper($fullName, $item);
            }
        } else {
            $this->attr[$name] = $value;
        }
    }

    public function escape($text)
    {
        $text = preg_replace('/\[/', '`{`', $text);
        $text = preg_replace('/\]/', '`}`', $text);
        $text = preg_replace('/\'/', '``', $text);
        return $text;
    }

    protected function getItemsByParent($parent_id)
    {
        $items = array_filter($this->setting, function ($v) use ($parent_id) {
            return $v['parent'] == $parent_id;
        });

        usort($items, function($a, $b) {
            if ($a['sort_order'] == $b['sort_order']) {
                return 0;
            }
            return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
        });

        return $items;
    }
}
