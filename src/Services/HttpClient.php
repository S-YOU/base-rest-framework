<?php

namespace Si6\Base\Services;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

trait HttpClient
{
    /** @var ClientInterface $client */
    protected $client;

    protected $options = [];

    protected $syncException = true;

    protected function setupClient()
    {
        $this->setBaseUri();
        $this->setDefaultHeaders();
        $this->client = app(ClientInterface::class, $this->options);
    }

    protected function setBaseUri()
    {
        $this->options['base_uri'] = $this->baseUri();
    }

    protected function get($url, $data = [], $options = [])
    {
        return $this->query('GET', $url, $data, $options);
    }

    protected function post($url, $data = [], $options = [])
    {
        return $this->json('POST', $url, $data, $options);
    }

    protected function put($url, $data = [], $options = [])
    {
        return $this->json('PUT', $url, $data, $options);
    }

    protected function patch($url, $data = [], $options = [])
    {
        return $this->json('PATCH', $url, $data, $options);
    }

    protected function delete($url, $data = [], $options = [])
    {
        return $this->query('DELETE', $url, $data, $options);
    }

    protected function query($method, $url, $data, $options)
    {
        $options = array_merge($options, ['query' => $data]);

        return $this->request($method, $url, $options);
    }

    protected function json($method, $url, $data, $options)
    {
        $options = array_merge($options, ['json' => $data]);

        return $this->request($method, $url, $options);
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return mixed
     */
    protected function request($method, $url, $options)
    {
        try {
            $data = $this->handleRequest($method, $url, $options);
        } catch (RequestException $exception) {
            $data = $this->handleException($exception);
        }

        return $data;
    }

    public function disableSyncException()
    {
        $this->syncException = false;

        return $this;
    }
}
