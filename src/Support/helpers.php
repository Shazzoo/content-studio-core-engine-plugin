<?php

if (! function_exists('locale_date')) {
    function locale_date(string $date): string
    {
        $locale = app()->getLocale();

        $dateTime = new DateTime($date);

        $fm = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $formattedDate = $fm->format($dateTime);

        return $formattedDate;
    }
}

if (! function_exists('read_time')) {
    function read_time(string $content): string
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200);

        return $minutes;
    }
}

if (! function_exists('author_initials')) {
    function author_initials(string $authorName): string
    {
        $initials = explode(' ', $authorName);
        $initials = array_map(function ($initial) {
            return strtoupper($initial[0]);
        }, $initials);

        return implode('', $initials);
    }
}
