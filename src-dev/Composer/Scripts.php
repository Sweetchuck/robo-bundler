<?php

namespace Sweetchuck\Robo\Bundler\Composer;

use Composer\Script\Event;
use Symfony\Component\Process\Process;
use Sweetchuck\GitHooks\Composer\Scripts as GitHooks;

class Scripts
{
    /**
     * @var \Composer\Script\Event
     */
    protected static $event;

    /**
     * @var string
     */
    protected static $bundlerVersion = '1.13.6';

    /**
     * @var \Closure
     */
    protected static $processCallbackWrapper;

    public static function postInstallCmd(Event $event): bool
    {
        $return = [];

        if ($event->isDevMode()) {
            static::init($event);

            $return[] = GitHooks::deploy($event);
            $return[] = static::bundleCheckAndInstall($event);
        }

        return count(array_keys($return, false, true)) === 0;
    }

    public static function postUpdateCmd(Event $event): bool
    {
        $return = [];

        if ($event->isDevMode()) {
            static::init($event);

            $return[] = GitHooks::deploy($event);
        }

        return count(array_keys($return, false, true)) === 0;
    }

    public static function bundleCheckAndInstall(Event $event): bool
    {
        $return = true;

        if ($event->isDevMode()) {
            static::init($event);

            $cmdPattern = [
                'cd %s',
                'gem install bundler:%s --no-document',
                'export BUNDLE_GEMFILE=%s',
                'bundle check || bundle install',
            ];
            $cmdArgs = [
                'tests/_data',
                static::$bundlerVersion,
                'Gemfile.success.rb',
                static::$bundlerVersion,
            ];

            $process = new Process(vsprintf(implode(' && ', $cmdPattern), $cmdArgs));
            $exitCode = $process->run(static::$processCallbackWrapper);

            $return = !$exitCode;
        }

        return $return;
    }

    protected static function init(Event $event)
    {
        if (static::$event) {
            return;
        }

        static::$event = $event;
        static::$processCallbackWrapper = function (string $type, string $text) {
            static::processCallback($type, $text);
        };
    }

    protected static function processCallback(string $type, string $text)
    {
        if ($type === Process::OUT) {
            static::$event->getIO()->write($text);
        } else {
            static::$event->getIO()->writeError($text);
        }
    }
}
