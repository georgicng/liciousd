<?php

namespace Gaiproject\Theme\Service;

class ShortcodeService
{
    private array $shortcodes = [];

    public function register(string $name, $callback)
    {
        $this->shortcodes[$name] = $callback;
    }

    public function render($content)
    {
        return preg_replace_callback('/\[(\w+)([^\]]*)\]/', function ($matches) {
            $name = $matches[1];
            $params = $this->parseParams($matches[2]);

            return isset($this->shortcodes[$name]) ? call_user_func($this->shortcodes[$name], $params) : $matches[0];
        }, $content);
    }

    protected function parseParams($string)
    {
        $params = [];
        preg_match_all('/(\w+)="([^"]+)"/', $string, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $params[$match[1]] = $match[2];
        }
        return $params;
    }
}