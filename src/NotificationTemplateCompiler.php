<?php

namespace DH\NotificationTemplates;

use DH\NotificationTemplates\Interfaces\NotificationTemplateInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Compilers\CompilerInterface;

class NotificationTemplateCompiler extends BladeCompiler implements CompilerInterface
{
    public function __construct(Filesystem $filesystem)
    {
        // Get Current Blade Instance
        $blade = app('view')->getEngineResolver()->resolve('blade')->getCompiler();

        $cache_path = sys_get_temp_dir();

        parent::__construct($filesystem, $cache_path);
        $this->rawTags = $blade->rawTags;
        $this->contentTags = array_map('stripcslashes', $blade->contentTags);
        $this->escapedTags = array_map('stripcslashes', $blade->escapedTags);
        $this->extensions = $blade->getExtensions();
        $this->customDirectives = $blade->getCustomDirectives();
    }

    public function compile($path = null)
    {
        if (is_null($path)) {
            return;
        }

        if (is_string($path)) {
            $notification = new $path;
        }

        if ($path instanceof NotificationTemplateInterface) {
            $notification = $path;
        }

        /** @var NotificationTemplateInterface $template */
        $template = $notification->getTemplate();

        $contents = $this->compileString($template->getTemplate());

        if (!is_null($this->cachePath)) {
            $this->files->put($this->getCompiledPath($path), $contents);
        }
    }

    public function getCompiledPath($path)
    {
        $path = strtolower(str_replace(["\\"], '_', $path));

        return $this->cachePath . '/' . md5($path);
    }

    public function isExpired($path): bool
    {
        return true;
    }
}