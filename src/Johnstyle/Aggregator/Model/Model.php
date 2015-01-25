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
    /** @var string $__source */
    private $__source;

    /** @var string $title */
    private $title;

    /** @var string $link */
    private $link;

    /** @var string $description */
    private $description;

    /** @var string $pubDate */
    private $pubDate;

    /** @var string $guid */
    private $guid;

    /** @var array $categories */
    private $categories = array();

    /** @var array $enclosures */
    private $enclosures = array();

    /**
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {

            return $this->{$name};
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        if(property_exists($this, $name)) {

            $value = $this->prepareValue($value);

            switch($name) {

                case 'pubDate':

                    $value = date('c', strtotime($value));
                    break;
            }

            $this->{$name} = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @param  mixed $value
     * @return string
     */
    protected function prepareValue($value)
    {
        if(is_array($value)) {

            foreach($value as &$val) {

                $val = $this->prepareValue($val);
            }

        } else {

            $value = trim(strip_tags((string) $value));
        }

        return $value;
    }
}
