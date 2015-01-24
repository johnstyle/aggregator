<?php
/**
 * Aggregator
 *
 * PHP version 5
 *
 * @package  Johnstyle\Aggregator
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/aggregator
 */

namespace Johnstyle\Aggregator;

/**
 * Class Aggregator
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Johnstyle\Aggregator
 */
class Aggregator
{
    const LANGUAGE = 'en-US';
    const CHARSET = 'UTF-8';
    const CACHE_TIME = 3600;

    /**
     * @param string   $title
     * @param string   $description
     * @param \closure $callback
     */
    public static function display($title, $description, \closure $callback)
    {
        $file = sys_get_temp_dir() . '/' . md5($title);

        if(!file_exists($file)
            || filemtime($file) > time() + static::CACHE_TIME) {

            $items = call_user_func_array($callback, array());

            ob_start(function($content) use($file) {
                file_put_contents($file, $content);
                return null;
            });

            include 'View/rss2.php';

            ob_end_flush();
        }

        header('Content-Type: application/rss+xml; charset=' . static::CHARSET);
        header('Content-Size: ' . filesize($file));
        readfile($file);
    }

    /**
     * @return string
     */
    public static function getCurrentDate()
    {
        return date('c');
    }
}
