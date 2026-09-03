<?php

namespace Shazzoo\StrategyEngine;

class Plugin
{
    public static function key(): string
    {
        return 'shazzoo/strategy-engine-plugin';
    }

    public static function provider(): string
    {
        return PluginServiceProvider::class;
    }
}
