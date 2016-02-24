<?php

namespace Caramel;


/**
 * Class Template
 *
 * @package Caramel
 */
class Template
{
    /** @var Caramel $crml */
    private $crml;


    public function __construct($crml)
    {
        $this->crml = $crml;
    }


    /**
     * Renders and includes the passed file
     *
     * @param $file
     */
    public function show($file)
    {
        $templateFile = $this->parse($file);

        # add the file header if wanted
        $fileHeader = $this->crml->config()->get("file_header");
        if ($fileHeader) {
            echo "<!-- " . $fileHeader . " -->";
        }

        # scoped Caramel
        $__CRML = $this;

        include $templateFile;
    }


    /**
     * adds a template directory
     *
     * @param $dir
     * @return string
     */
    public function add($dir)
    {
        return $this->crml->directories()->add($dir, "templates.dirs");
    }


    /**
     * removes a template directory
     *
     * @param $dir
     * @return string
     */
    public function remove($dir)
    {
        return $this->crml->directories()->remove($dir, "templates.dirs");
    }


    /**
     * returns all template directories
     *
     * @return array
     */
    public function dirs()
    {
        return $this->crml->directories()->get("templates.dirs");
    }


    /**
     * Renders and returns the passed file
     *
     * @param $file
     * @return string
     */
    public function fetch($file)
    {
        $templateFile      = $this->parse($file);
        $return            = array();
        $return["file"]    = $templateFile;
        $return["content"] = file_get_contents($templateFile);

        return $return;
    }


    /**
     * parsed a template file
     *
     * @param $file
     * @return mixed|string
     */
    public function parse($file)
    {
        if ($this->crml->cache()->modified($file)) {
            $lexed = $this->crml->lexer()->lex($file);
            $this->crml->parser()->parse($lexed["file"], $lexed["dom"]);
        }

        return $this->crml->cache()->getPath($file);
    }
}