<?php

namespace Sweetchuck\Robo\Bundler;

class Utils
{
    public static function filterEnabled(array $items): array
    {
        return (gettype(reset($items)) === 'boolean') ?
            array_keys($items, true, true)
            : $items;
    }

    public static function parseRubyVersion(string $version): array
    {
        $pattern = '/^(?P<major>\d+)\.(?P<minor>\d+)\.(?P<fix>\d+)(p(?P<patch>\d+)){0,1}$/';
        $versionParts = [
            'full' => $version,
            'base' => '',
            'major' => '',
            'minor' => '',
            'fix' => '',
            'patch' => '',
        ];
        $matches = [];
        if (preg_match($pattern, $version, $matches)) {
            $versionParts['major'] = (int) $matches['major'];
            $versionParts['minor'] = (int) $matches['minor'];
            $versionParts['fix'] = (int) $matches['fix'];
            $versionParts['patch'] = isset($matches['patch']) ? (int) $matches['patch'] : null;
            $versionParts['base'] = "{$versionParts['major']}.{$versionParts['minor']}.{$versionParts['fix']}";
        }

        return $versionParts;
    }
}
