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

use Johnstyle\Aggregator\Model\Service\Feed;

/**
 * Class Aggregator
 *
 * @author  Jonathan SAHM <contact@johnstyle.fr>
 * @package Johnstyle\Aggregator
 */
class Aggregator
{
    const DEFAULT_LANGUAGE = 'fr-FR';
    const DEFAULT_CHARSET = 'UTF-8';
    const DEFAULT_CACHE_TIME = 600;

    /** @var string $publicPath */
    protected static $publicPath;

    /** @var string $language */
    protected $language;

    /** @var string $charset */
    protected $charset;

    /** @var string $cacheTime */
    protected $cacheTime;

    /** @var string $filename */
    protected $filename;

    /** @var string $file */
    protected $file;

    /** @var string $contentType */
    protected $contentType;

    /** @var array $items */
    protected $items = array();

    /** @var bool $stop */
    protected $stop = false;

    /**
     * @param  string $filename
     * @param  string $language
     * @param  string $charset
     * @param  int    $cacheTime
     * @throws AggregatorException
     */
    public function __construct($filename, $language = self::DEFAULT_LANGUAGE, $charset = self::DEFAULT_CHARSET, $cacheTime = self::DEFAULT_CACHE_TIME)
    {
        $this->filename = $filename;
        $this->file = static::getPublicPath() . $filename;
        $this->contentType = substr($filename, strpos($filename, '.')+1);
        $this->language = $language;
        $this->charset = $charset;
        $this->cacheTime = $cacheTime;

        if(!in_array($this->contentType, array('rss', 'atom', 'json'))) {

            throw new AggregatorException('Invalid content-type');
        }

        if(file_exists($this->file)
            && filemtime($this->file) > time() - $this->cacheTime) {

            $this->stop();
        }
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = array_merge($this->items, $items);
    }

    /**
     * @param string $path
     */
    public static function setPublicPath($path)
    {
        static::$publicPath = $path;
    }

    /**
     * @return string
     * @throws AggregatorException
     */
    public static function getPublicPath()
    {
        if(is_null(static::$publicPath)) {

            throw new AggregatorException('Configure Aggregator public path');
        }

        return static::$publicPath;
    }

    /**
     * @param  mixed $feeds
     * @return static
     */
    public function parseFeed($feeds)
    {
        if($this->isStoped()) {

            return $this;
        }

        if(!is_array($feeds)) {

            $feeds = array(
                'default' => $feeds
            );
        }

        foreach($feeds as $source=>$feed) {

            $items = Feed::fetch((new Curl())->get($feed)->content('xml'));

            foreach($items as &$item) {

                $item['__source'] = $source;
            }

            $this->setItems($items);
        }

        return $this;
    }

    /**
     * @param  callable $callback
     * @return $this
     */
    public function parseCallback(\closure $callback)
    {
        if($this->isStoped()) {

            return $this;
        }

        call_user_func_array($callback, array(&$this));

        return $this;
    }

    /**
     * @param  callable $callback
     * @return $this
     */
    public function loop(\closure $callback)
    {
        if($this->isStoped()) {

            return $this;
        }

        foreach($this->items as $i=>&$item) {

            call_user_func_array($callback, array(&$item));

            if(is_null($item)) {

                unset($this->items[$i]);
            }
        }

        return $this;
    }

    /**
     * @param  string     $name
     * @param  int|string $order
     * @return $this
     */
    public function sort($name = 'pubDate', $order = SORT_DESC)
    {
        if($this->isStoped()) {

            return $this;
        }

        $arraySort = array();

        foreach($this->items as $i=>$item) {

            $arraySort[$i] = array_key_exists($name, $item) ? $item[$name] : null;
        }

        array_multisort($arraySort, $order, SORT_STRING, $this->items);

        return $this;
    }

    /**
     * @param  int $count
     * @return $this
     */
    public function truncate($count = 20)
    {
        if($this->isStoped()) {

            return $this;
        }

        $this->items = array_slice($this->items, 0, $count);

        return $this;
    }

    /**
     * @return $this
     */
    public function followLinks()
    {
        if($this->isStoped()) {

            return $this;
        }

        foreach($this->items as &$item) {

            $url = (new Curl())->getFollowLink($item['link']);

            if ('' !== (string)$url) {

                $item['link'] = $url;
            }
        }

        return $this;
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function save(array $options)
    {
        if($this->isStoped()) {

            return $this;
        }

        $options = array_merge(array(
            'title' => null,
            'description' => null
        ), $options);

        $path = dirname($this->file);

        if(!is_dir($path)) {

            mkdir($path, 0775, true);
        }

        switch($this->contentType) {

            case 'atom':
            case 'rss':

                ob_start(function($content) {
                    file_put_contents($this->file, $content, LOCK_EX);
                    return null;
                });

                include 'View/' . $this->contentType . '.php';

                ob_end_flush();
                break;

            case 'json':

                file_put_contents($this->file, json_encode($this->items), LOCK_EX);
                break;
        }

        return $this;
    }

    protected function stop()
    {
        $this->stop = true;
    }

    /**
     * @return bool
     */
    protected function isStoped()
    {
        return $this->stop === true;
    }
}
