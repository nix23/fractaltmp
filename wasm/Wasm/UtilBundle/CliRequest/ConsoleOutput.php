<?php
namespace Wasm\UtilBundle\CliRequest;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\DomCrawler\Crawler;
use Wasm\UtilBundle\Util\Str;

class ConsoleOutput
{
    private $output;

    public function __construct()
    {
        $this->output = new SymfonyConsoleOutput();

        $successStyle = new OutputFormatterStyle('white', 'green');
        $errStyle = new OutputFormatterStyle('white', 'red');

        $this->output->getFormatter()->setStyle('success', $successStyle);
        $this->output->getFormatter()->setStyle('err', $errStyle);
    }

    public function writeln($string)
    {
        $this->output->writeln($string);
    }

    public function printR($request, $response)
    {
        $this->printHead($request, $response);
        $this->printBody($request, $response);
    }

    private function printHead($request, $response)
    {
        $requestInfo = $request->getMethod() . ' ' . $response->getStatusCode();
        $requestInfo .= ' ' . $response->getReasonPhrase();

        $this->output->writeln('');

        if($response->getStatusCode() == 500)
            $text = '<error>' . $requestInfo . '</error>: ';
        else 
            $text = '<success>' . $requestInfo . '</success>: ';

        $text .= '<info>' . $request->getUri() . '</info>';
        $this->output->writeln($text);

        foreach($response->getHeaders() as $name => $values) 
            $this->writeln(sprintf('%s: %s', $name, implode(', ', $values)));

        $this->output->writeln('');
    }

    private function printBody($request, $response)
    {
        $body = (string)$response->getBody();

        $contentType = $response->getHeader('Content-Type');
        $contentType = $contentType[0];

        if($contentType == 'application/json' || 
           strpos($contentType, '+json') !== false) {
            $this->printJson($body);
        } 
        else {
            $this->printFromHtml($body, $response);
        }

        $this->output->writeln('');
    }

    private function printJson($body)
    {
        $data = json_decode($body);
        $dataArr = json_decode($body, true); 

        if($data === null) 
            $this->output->writeln($body); // Broken JSON
        else {
            if($this->isUnhandledException($dataArr))
                $this->printUnhandledException($dataArr);
            else
                $this->output->writeln(json_encode($data, JSON_PRETTY_PRINT));
        }
    }

    private function isUnhandledException($data)
    {  
        if(is_array($data) &&
           array_key_exists("error", $data) &&
           array_key_exists("exception", $data["error"]) &&
           array_key_exists("class", $data["error"]["exception"][0]))
            return true;

        return false;
    }

    private function printUnhandledException($data)
    {
        $eData = $data["error"]["exception"][0];
        $eHead = $eData["class"] . ": " . $eData["message"];

        $this->output->writeln("<err>$eHead</err>");

        if(!array_key_exists("trace", $eData))
            return;

        foreach($eData["trace"] as $traceRow) { 
            $row = "";
            $row .= $this->getLineParamForPrint($traceRow["line"]);

            $row .= $traceRow["class"] . $traceRow["type"] . $traceRow["function"];

            if(array_key_exists("args", $traceRow) && count($traceRow["args"]) > 0)
                $row .= $this->getTraceParamsForPrint($traceRow["args"]);

            $this->output->writeln($row);

            if($traceRow["file"] != null) {
                $f = $traceRow["file"];
                $this->output->writeln("          <question>$f</question>");
            }
        }
    }

    private function getLineParamForPrint($line)
    {
        if($line == null)
            return "<success>UA</success>:   ";

        $count = Str::len($line);
        $line = "<success>" . $line . "</success>:";

        if($count < 5) {
            $spacesToAdd = 5 - $count;
            for($i = 0; $i < $spacesToAdd; $i++)
                $line .= " ";
        }

        return $line;
    }

    private function getTraceParamsForPrint($params) 
    {
        $rows = array();
        foreach($params as $param) {
            $row = $param[0] . "=";
            if(gettype($param[1]) == "string")
                $row .= $param[1];
            else if(gettype($param[1]) == "array")
                $row .= "arrayData";
            else if(gettype($param[1]) == "object")
                $row .= "objectData";
            else if(gettype($param[1]) == "boolean")
                $row .= ($param[1]) ? "true" : "false";
            else if(gettype($param[1]) == "NULL")
                $row .= "NULL";
            else
                $row .= "resourceOrUnknown";

            $rows[] = $row;
        }

        return "(" . implode(",", $rows) . ")"; 
    }

    private function printFromHtml($body, $response)
    {
        // the response is HTML - see if we should print all of it or some of it
        $isValidHtml = strpos($body, '</body>') !== false;

        if($isValidHtml) 
            $this->printFromValidHtml($body, $response);
        else
            $this->output->writeln($body);
    }

    private function printFromValidHtml($body, $response)
    {
        $this->output->writeln('');
        $crawler = new Crawler($body);

        // very specific to Symfony's error page
        $isError = $crawler->filter('#traces-0')->count() > 0
            || strpos($body, 'looks like something went wrong') !== false;
        if($isError) {
            $this->output->writeln('There was an Error!!!!');
            $this->output->writeln('');
        } 
        else {
            $this->output->writeln('HTML Summary (h1 and h2):');
        }

        // finds the h1 and h2 tags and prints them only
        foreach($crawler->filter('h1, h2')->extract(array('_text')) as $header) {
            // avoid these meaningless headers
            if(strpos($header, 'Stack Trace') !== false) 
                continue;
            if(strpos($header, 'Logs') !== false) 
                continue;

            // remove line breaks so the message looks nice
            $header = str_replace("\n", ' ', trim($header));
            // trim any excess whitespace "foo   bar" => "foo bar"
            $header = preg_replace('/(\s)+/', ' ', $header);

            if($isError)
                $this->output->writeln("<err>$header</err>");
            else 
                $this->output->writeln($header);
        }

        $profilerUrl = $response->getHeader('X-Debug-Token-Link');
        if($profilerUrl) {
            $fullProfilerUrl = $profilerUrl[0]; // Add base uri from Req
            $this->output->writeln('');
            $this->output->writeln(sprintf(
                'Profiler URL: <comment>%s</comment>',
                $fullProfilerUrl
            ));
        }
    }
}