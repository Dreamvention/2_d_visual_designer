<?php
namespace d_shortcode_reader_writer;
class shortcode_reader
{
    private $setting;
    private $items;
    private $parents = array();
    private $sort_order = 0;
    private $sort_orders = array();
    private $level= 0;

    public function __construct($items){
        $this->items = $items;
    }

    /**
     * Converts shortcodes from text to a single-level array
     */
    public function readShortcode($text)
    {
        $this->setting = array();
        preg_replace_callback('/' . $this->getPattern() . '/s', array( &$this, "do_shortcode_tag" ), $text);

        return $this->setting;
    }

    protected function do_shortcode_tag($m) {
        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];

        $type=str_replace('vd_','',$tag);

        $attr = $this->shortcode_parse_atts($m[3]);

        if ( !empty( $m[5] ) ) {

            $current_block = $type.'_'.$this->getRandomString();

            if(!isset($this->sort_orders[$this->level])){
                $this->sort_orders[$this->level] = 0;
            }
            else{
                $this->sort_orders[$this->level]++;
            }

            if(!empty($this->parents))
            {
                $parent_id = current(array_slice($this->parents, -1));
            }
            else{
                $parent_id = '';
            }

            $this->setting[$current_block] = array(
                'setting' => $attr,
                'id' => $current_block,
                'parent' => $parent_id,
                'sort_order' => $this->sort_orders[$this->level],
                'type' => $type
                );

            array_push($this->parents,$current_block);
            $this->level++;
            $content_child = $this->parseDescriptionHelper($m[5]);

            return '';

        } else {
            $current_block = $type.'_'.$this->getRandomString();
            $parent_id = current(array_slice($this->parents, -1));
            $this->setting[$current_block] = array(
                'setting' => $attr,
                'id' => $current_block,
                'parent' => $parent_id,
                'sort_order' => $this->sort_order++,
                'type' => $type
                );

            return '';
        }
    }

    public function parseDescriptionHelper($description){
        $this->sort_orders[$this->level] = -1;
        $content = preg_replace_callback('/' . $this->getPattern() . '/s', array( &$this, "do_shortcode_tag" ), $description);
        array_pop($this->parents);
        $this->level--;
        return $content;
    }


    /**
     * Returns attributes from a string
     */

    public function shortcode_parse_atts($text) {

        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|([a-zA-Z:0-9_]+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
            $params = '';
            $attr = array();
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $attr[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $this->parseName($m[3], $m[4], $attr);
                }
            }
        } else {
            $attr = ltrim($text);
        }

        return $attr;
    }

    /**
     * Converts the variable name to an array
     */

    public function parseName($name, $value, &$attr){
        $pos = strpos($name, '::');
        if($pos === false){

            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            $value = $this->unescape($value);
            $attr[$name] = $value;
        }
        else{
            $name = str_replace('::',',',$name);
            $name = str_replace(':',',',$name);
            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            $value = $this->unescape($value);
            $exploded = explode(',', $name);
            $path = '';
            $temp = &$attr;
            foreach($exploded as $key) {
                $temp = &$temp[$key];
            }
            $temp = $value;
        }
    }

    /**
     * Returns a unique id
     */

    public function getRandomString(){
        return substr( md5(rand()), 0, 7);
    }

    public function unescape($text){

        $text = str_replace('`{`', '[', $text);
        $text = str_replace('`}`', ']', $text);
        $text = str_replace('``', "'", $text);

        return $text;
    }

    /**
     * Returns the pattern for processing shortcodes
     */

    public function getPattern(){
        $pattern = "\\[(\\[?)(vd_row|vd_column";

        foreach ($this->items as $block) {
            $pattern .= '|vd_'.$block;
        }
        $pattern .=")(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\[(?!\\/\\2\])[^\\[]*+)*+)\[\\/\\2\])?)(\\]?)";
        return $pattern;
    }
}
