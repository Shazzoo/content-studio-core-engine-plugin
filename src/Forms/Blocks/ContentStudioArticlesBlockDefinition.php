<?php

namespace Shazzoo\StrategyEngine\Forms\Blocks;

use Shazzoo\ContentStudioCore\Support\Blocks\BlockDefinition;
use Shazzoo\ContentStudioCore\Support\Fields\Definitions\TextInput;
use Shazzoo\ContentStudioCore\Support\Fields\Definitions\ToggleField;

final class ContentStudioArticlesBlockDefinition
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('shazzoo/content-studio.content-studio-articles')
            ->label('Strategy Engine Articles')
            ->description('Show recent Strategy Engine articles inside any page.')
            ->group('Plugins')
            ->icon('heroicon-o-newspaper')
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->default('Latest Articles'),
                ToggleField::make('show_excerpt')
                    ->label('Show excerpt')
                    ->default(true),
            ]);
    }
}
