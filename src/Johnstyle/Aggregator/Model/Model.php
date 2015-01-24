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

namespace Johnstyle\Aggregator\Model;

/**
 * Class Model
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Johnstyle\Aggregator\Model
 */
abstract class Model
{
    /** @var string $title */
    public $title;

    /** @var string $link */
    public $link;

    /** @var string $description */
    public $description;

    /** @var string $pubDate */
    public $pubDate;

    /** @var string $guid */
    public $guid;

    /** @var array $categories */
    public $categories = array();

    /** @var array $enclosures */
    public $enclosures = array();
}
