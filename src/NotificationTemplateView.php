<?php

namespace DH\NotificationTemplates;

use ArrayAccess;
use Composer\Autoload\ClassLoader;
use DH\NotificationTemplates\Interfaces\NotificationTemplateInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Illuminate\View\Factory;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class NotificationTemplateView extends View implements ArrayAccess, Renderable
{
    protected string $theme = 'default';

    public function __construct(Factory $factory, NotificationTemplateCompilerEngine $engine)
    {
        $this->engine = $engine;
        $this->factory = $factory;
        $this->factory->replaceNamespace(
            'mail', $this->htmlComponentPaths()
        );
    }

    protected function getVendorPath(): string
    {
        $reflector = new \ReflectionClass(ClassLoader::class);
        $vendorPath = preg_replace('/^(.*)\/composer\/ClassLoader\.php$/', '$1', $reflector->getFileName());
        if ($vendorPath && is_dir($vendorPath)) {
            return $vendorPath . '/';
        }
    }

    protected function componentPaths(): array
    {
        return array_unique(array_merge([], [
            $this->getVendorPath() . '/laravel/framework/src/Illuminate/Mail/resources/views',
        ]));
    }

    public function htmlComponentPaths(): array
    {
        return array_map(function ($path) {
            return $this->getVendorPath() . '/laravel/framework/src/Illuminate/Mail/resources/views/html';
        }, $this->componentPaths());
    }

    public function make(NotificationTemplateInterface $message, $data = [], $mergeData = [], $content_field = null): self
    {
        $this->path = $message;
        $this->data = array_merge($data, $mergeData);

        return $this;
    }

    public function render(callable $callback = null)
    {
        $this->factory->flushFinderCache();


        $contents = $this->renderContents();

        // TODO
        $theme = 'mail::themes.' . $this->theme;


        $contents = new HtmlString((new CssToInlineStyles)->convert(
            $contents, $this->factory->make($theme, $this->data)->render()
        ));

        $response = isset($callback) ? $callback($this, $contents) : null;

        $this->factory->flushStateIfDoneRendering();

        return $response ?: $contents;
    }

    protected function renderContents()
    {
        // We will keep track of the amount of views being rendered so we can flush
        // the section after the complete rendering operation is done. This will
        // clear out the sections for any separate views that may be rendered.
        $this->factory->incrementRender();

        $contents = $this->getContents();

        // Once we've finished rendering the view, we'll decrement the render count
        // so that each sections get flushed out next time a view is created and
        // no old sections are staying around in the memory of an environment.
        $this->factory->decrementRender();

        return $contents;
    }

    protected function getContents()
    {
        if ($this->path instanceof NotificationTemplateInterface) {
            $this->path = $this->path->getNotification();
        }

        return parent::getContents();
    }
}