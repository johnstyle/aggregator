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
 * Class Twitter
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Johnstyle\Aggregator\Model\Service
 */
class Twitter extends Model
{
    /**
     * @param  array $response
     * @return array
     */
    public static function fetch($response)
    {
        $items = array();

        if($response) {

            foreach($response as $data) {

                $title = null;
                $link = null;
                $tags = array();
                $media = array();

                if(preg_match_all('#\#([^\s]+)#si', $data->text, $matches)) {

                    $tags = $matches[1];
                }

                if(isset($data->entities->urls)
                    && count($data->entities->urls)) {

                    $link = $data->entities->urls[0]->expanded_url;
                }

                if(isset($data->entities->media)
                    && count($data->entities->media)) {

                    $mediaUrl = $data->entities->media[0]->media_url;
                    $mediaHeaders = get_headers($mediaUrl, 1);

                    $media[] = array(
                        'url' => $mediaUrl,
                        'length' => $mediaHeaders['Content-Length'],
                        'type' => $mediaHeaders['Content-Type']
                    );
                }

                $title = trim(preg_replace('#(^|\s)(https?://)?[a-z0-9\-\.]+\.[a-z]+(/\S*)?#si', ' ', $data->text));

                $model = new static();
                $model->title = $title;
                $model->description = $data->text;
                $model->link = $link;
                $model->pubDate = date('c', strtotime($data->created_at));
                $model->guid = 'https://twitter.com/' . $data->user->screen_name . '/status/' . $data->id;
                $model->categories = $tags;
                $model->enclosures = $media;

                $items[] = $model;
            }
        }

        return $items;
    }
}
