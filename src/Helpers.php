<?php

namespace Nickfairchild\Wpise;

use Illuminate\Container\Container;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Style\SymfonyStyle;

class Helpers
{
    public static function abort(string $text): void
    {
        static::danger($text);

        exit(1);
    }

    public static function app(string|null $name = null): mixed
    {
        return $name ? Container::getInstance()->make($name) : Container::getInstance();
    }

    public static function ask(string $question, mixed $default = null): mixed
    {
        $style = new SymfonyStyle(static::app('input'), static::app('output'));

        return $style->ask($question, $default);
    }

    public static function comment(string $text): void
    {
        static::app('output')->writeln('<comment>'.$text.'</comment>');
    }

    public static function confirm(string $question, bool $default = true): mixed
    {
        $style = new SymfonyStyle(static::app('input'), static::app('output'));

        return $style->confirm($question, $default);
    }

    public static function danger(string $text): void
    {
        static::app('output')->writeln('<fg=red>'.$text.'</>');
    }

    public static function warn(string $text): void
    {
        static::app('output')->writeln('<fg=yellow>'.$text.'</>');
    }

    public static function info(string $text): void
    {
        static::app('output')->writeln('<info>'.$text.'</info>');
    }

    public static function line(string $text = ''): void
    {
        static::app('output')->writeln($text);
    }

    public static function menu(string $title, mixed $choices): mixed
    {
        $style = new SymfonyStyle(static::app('input'), static::app('output'));

        return $style->askQuestion(new KeyChoiceQuestion($title, $choices));
    }

    public static function secret(string $question): mixed
    {
        $style = new SymfonyStyle(static::app('input'), static::app('output'));

        return $style->askHidden($question);
    }

    public static function step(string $text): void
    {
        static::line('<fg=blue>==></> '.$text);
    }

    public static function table(array $headers, array $rows, string $style = 'borderless')
    {
        if (empty($rows)) {
            return;
        }

        $table = new Table(static::app('output'));

        $table->setHeaders($headers)->setRows($rows)->setStyle($style)->render();
    }

    public static function time_ago(string $date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public static function write(string $text): void
    {
        static::app('output')->write($text);
    }

    public static function replaceInFile(string $file, array $replacements): void
    {
        $contents = file_get_contents($file);

        file_put_contents(
            $file,
            str_replace(
                array_keys($replacements),
                array_values($replacements),
                $contents
            )
        );
    }
}
