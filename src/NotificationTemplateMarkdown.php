<?php

namespace CourseLink\NotificationTemplates;

use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class NotificationTemplateMarkdown extends Markdown
{
    public function render($view, array $data = [], $inliner = null)
    {
        $this->view->flushFinderCache();

        $this->view->replaceNamespace('mail', $this->htmlComponentPaths());

        $contents = Blade::render($view, $data);

        if ($this->view->exists($customTheme = Str::start($this->theme, 'mail.'))) {
            $theme = $customTheme;
        } else {
            $theme = str_contains($this->theme, '::')
                ? $this->theme
                : 'mail::themes.' . $this->theme;
        }

        return new HtmlString(($inliner ?: new CssToInlineStyles)->convert(
            $contents, $this->view->make($theme, $data)->render()
        ));
    }
}