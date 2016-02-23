<?php

namespace Caramel;

/**
 *
 * Class PluginFor
 * @package Caramel
 *
 * @description: handle foreach loops of variables
 * @position: 4
 * @author: Stefan Hövelmanns
 * @License: MIT
 *
 */

class PluginFor extends Plugin
{

    /** @var int $position */
    protected $position = 4;

    /**
     * @param Node $node
     * @return bool
     */
    public function check($node)
    {
        return ($node->get("tag/tag") == "for");
    }

    /**
     * @param Node $node
     * @return Node $node
     */
    public function process($node)
    {
        echo "your custom plugin";
        return $node;
    }

}