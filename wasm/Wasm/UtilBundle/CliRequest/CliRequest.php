<?php
namespace Wasm\UtilBundle\CliRequest;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Wasm\UtilBundle\Util\Str;

class CliRequest
{
    private $handler = null;
    private $history = array();

    private $client = null;
    private $consoleOutput = null;

    public function __construct($baseUri = "")
    {
        if(Str::len($baseUri) == 0)
            throw new \Exception("Please provide base uri.");

        $this->handler = HandlerStack::create();
        $this->handler->push(Middleware::history($this->history));

        $this->client = new GuzzleHttpClient([
            'base_uri' => $baseUri,
            // Normally, if our server returns a 400 or 500 status code, 
            // Guzzle blows up with an Exception. This makes it act normal - 
            // it'll return a Response always.
            'http_errors' => false,
            'handler' => $this->handler,
        ]);
        $this->consoleOutput = new ConsoleOutput();
    }

    public function resetHistory()
    {
        $this->history = array();
    }

    private function getLastRequest()
    {
        if(!$this->history || empty($this->history))
            return null;

        $history = $this->history;
        $last = array_pop($history);

        return $last['request'];
    }

    private function getLastResponse()
    {
        if(!$this->history || empty($this->history))
            return null;

        $history = $this->history;
        $last = array_pop($history);

        return $last['response'];
    }

    public function printR()
    {
        $lastRequest = $this->getLastRequest();
        if(!$lastRequest) 
            throw new \Exception("No requests were made.");

        $lastResponse = $this->getLastResponse();
        if(!$lastResponse) 
            throw new \Exception("No responses.(No requests were made)");

        $this->consoleOutput->printR($lastRequest, $lastResponse);
    }

    public function get($path = "", $params = array(), $headers = array())
    {
        $this->client->request('GET', $path, array(
            'query' => $params,
            'headers' => $headers,
        ));
        return $this;
    }

    public function post($path = "", $params = array(), $headers = array())
    {
        $headers['Content-Type'] = 'application/json';

        $this->client->request('POST', $path, array(
            'json' => $params,
            'headers' => $headers,
        ));
        return $this;
    }

    public function delete($path = "", $params = array(), $headers = array())
    {
        $headers['Content-Type'] = 'application/json';

        $this->client->request('DELETE', $path, array(
            'json' => $params,
            'headers' => $headers,
        ));
        return $this;
    }
}