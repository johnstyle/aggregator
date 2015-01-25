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

namespace Johnstyle\Aggregator\Model\Service;

use Johnstyle\Aggregator\Model\Model;

/**
 * Class Feed
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Johnstyle\Aggregator\Model\Service
 */
class Feed extends Model
{
    /**
     * @param  \SimpleXMLElement $xml
     * @return array
     */
    public static function fetch(\SimpleXMLElement $xml)
    {
        $items = array();

        if(isset($xml->channel->item)) {

            foreach($xml->channel->item as $item) {

                $model              = new Feed();
                $model->title       = (string) $item->children()->title;
                $model->description = (string) $item->children()->description;
                $model->link        = (string) $item->children()->link;
                $model->pubDate     = (string) $item->children()->pubDate;
                $model->guid        = (string) $item->children()->guid;

                $items[] = $model->toArray();
            }
        }

        return $items;
    }
}
