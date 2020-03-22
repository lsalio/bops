<?php
/**
 * This file is part of bops
 *
 * @copyright Copyright (C) 2020 Jayson Wang
 * @license   MIT License
 * @link      https://github.com/wjiec/php-bops
 */
namespace Bops\Error\Handler;

use Whoops\Exception\Frame;
use Whoops\Handler\Handler;


/**
 * Class Logger
 *
 * @package Bops\Error\Handler
 */
class Logger extends Handler {

    /**
     * @inheritDoc
     * @return int|null
     */
    public function handle() {
        if ($logger = container('logger')) {
            $logger->error($this->getLogContents());
        }
        return self::DONE;
    }

    /**
     * Returns contents of exception/error
     *
     * @return string
     */
    protected function getLogContents(): string {
        $exception = $this->getException();
        return sprintf("<%s:%d> %s: %s\n%s STACKTRACE %s\n%s",
            $exception->getFile(), $exception->getLine(),
            get_class($exception), $exception->getMessage(),
            str_pad('', 24, '='), str_pad('', 24, '='),
            $this->getStackTrace()
        );
    }

    /**
     * Get the exception trace as plain text
     *
     * @return string
     */
    protected function getStackTrace(): string {
        $frames = $this->getInspector()->getFrames();

        $stacktrace = '';
        foreach ($frames as $index => $frame) {
            /* @var Frame $frame */
            $stacktrace .= sprintf("#%-3d <%s:%d>\t%s::%s(%s)\n",
                count($frames) - $index - 1, $frame->getFile(), $frame->getLine(), $frame->getClass(),
                $frame->getFunction(), $this->getFunctionArgs($frame)
            );
        }
        return $stacktrace;
    }

    /**
     * Get the arguments of function in the frame
     *
     * @param Frame $frame
     * @return string
     */
    protected function getFunctionArgs(Frame $frame): string {
        return join(",", array_map(function($arg) {
            switch (true) {
                case is_string($arg):   return "string{{$arg}}";
                case is_numeric($arg):  return "number{{$arg}}";
                case is_bool($arg):     return "boolean{{$arg}}";
                case is_null($arg):     return "null{null}";
                case is_array($arg):    return "array{...}";
                case is_object($arg):   return sprintf('object{%s}', get_class($arg));
            }
            return '???{???}';
        }, $frame->getArgs()));
    }

}
