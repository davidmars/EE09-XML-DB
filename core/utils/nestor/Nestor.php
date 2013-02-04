<?php
/**
 * Nestor is a detective. He will not help you to to more than its job...inspect.
 */
class Nestor
{
    /**
     * @var float The start time
     */
    private static $startTime;
    /**
     * Use it to start inspecting, all durations will start from this point.
     */
    public static function start(){
        self::$startTime=microtime(true);
    }

    /**
     * @return float The current time.
     */
    public static function time(){
        return microtime(true)-self::$startTime;
    }
}
