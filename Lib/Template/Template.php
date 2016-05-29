<?php

namespace Temple\Template;


use Temple\Utilities\Config;
use Temple\Dependency\DependencyInstance;

class Template extends DependencyInstance
{

    /** @var  Lexer $Lexer */
    protected $Lexer;

    /** @var  Parser $Parser */
    protected $Parser;

    /** @var  Config $Config */
    protected $Config;

    public function dependencies()
    {
        return array(
            "Config",
            "Lexer",
            "Parser",
        );
    }

}