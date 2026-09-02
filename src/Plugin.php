<?php

namespace Shazzoo\ContentStudio;

class Plugin
{
    public static function key(): string
    {
        return 'shazzoo/content-studio-core-engine-plugin';
    }

    public static function provider(): string
    {
        return PluginServiceProvider::class;
    }
}
