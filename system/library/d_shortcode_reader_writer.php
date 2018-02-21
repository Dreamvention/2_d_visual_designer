<?php
use d_shortcode_reader_writer\shortcode_reader;
use d_shortcode_reader_writer\shortcode_writer;

class d_shortcode_reader_writer
{
    private $writer;
    private $reader;
    
    public function __construct($items)
    {
        $this->reader = new shortcode_reader($items);
        $this->writer = new shortcode_writer($items);
    }

    public function readShortcode($text)
    {
        return $this->reader->readShortcode($text);
    }

    public function writeShortcode($setting)
    {
        return $this->writer->writeShortcode($setting);
    }

    public function escape($text) {
        return $this->writer->escape($text);
    }
}
