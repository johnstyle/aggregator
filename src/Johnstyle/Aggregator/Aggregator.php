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

    /**
     * @param string $title
     * @param string $description
     * @param string $items
     */
    public static function display($title, $description, $items)
    {
        include 'View/rss2.php';
    }

    /**
     * @return string
     */
    public static function getCurrentDate()
    {
        return date('c');
    }
}
