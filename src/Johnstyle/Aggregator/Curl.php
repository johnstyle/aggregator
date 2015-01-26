<?php

namespace Johnstyle\Aggregator;

class Curl
{
    /** @var resource $resource */
    protected $resource;

    /** @var string $content */
    protected $content;

    public function __construct()
    {
        $this->resource = curl_init();

        curl_setopt($this->resource, CURLOPT_HEADER, false);
        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->resource, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($this->resource, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->resource, CURLOPT_MAXREDIRS, 5);
        curl_setopt($this->resource, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->resource, CURLOPT_SSL_VERIFYHOST, false);
    }

    /**
     * @return $this
     */
    public function close()
    {
        curl_close($this->resource);

        return $this;
    }

    /**
     * @param  string $url
     * @return $this
     */
    public function get($url)
    {
        curl_setopt($this->resource, CURLOPT_URL, $url);

        $this->content = curl_exec($this->resource);

        return $this;
    }

    /**
     * @param  string $type
     * @return mixed
     */
    public function content($type = null)
    {
        $this->close();

        switch($type) {

            case 'xml':

                $this->content = simplexml_load_string($this->content);
                break;
        }

        return $this->content;
    }

    /**
     * @param  string $url
     * @return string
     */
    public function getFollowLink($url)
    {
        curl_setopt($this->resource, CURLOPT_NOBODY, true);

        $this->get($url);

        $link = curl_getinfo($this->resource, CURLINFO_EFFECTIVE_URL);

        $this->close();

        return $link;
    }
}