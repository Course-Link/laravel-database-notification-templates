<?php

namespace DH\NotificationTemplates;

use Illuminate\View\Engines\CompilerEngine;

class NotificationTemplateCompilerEngine extends CompilerEngine
{
    public function __construct(NotificationTemplateCompiler $compiler)
    {
        parent::__construct($compiler);
    }
}