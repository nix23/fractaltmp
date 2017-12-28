<?php
use \Wasm\UtilBundle\CliRequest\CliRequest;
use \Wasm\UtilBundle\Debug\Debug;
// @todo -> Is CliRunner && WebRunner same script???
//       -> How to know which requests can be runned in Web/Cli-runner?
//          -> Scan all Namespace->Bundle Controllers, vs generate for each module
//          -> Or use both -> register manually + gen.-te for each module

require __DIR__.'/vendor/autoload.php';

$baseUri = 'http://fractal.lh/app_dev.php/';
$r = new CliRequest($baseUri);

$r->post('forms/submitformdemo', array(

))->printR();
exit();

// $r->get('forms', array(
//     "forms" => array(
//         "Api_Contact"
//         // "Pub_Landing_Contact" => array(
//         //     "state" => array(
//         //         "firstName" => "John",
//         //         "lastName" => "Smith",
//         //         "email" => "szdszd@inbox.lv",
//         //         //"message" => "faf af wa fwaf waf",
//         //     ),
//         // ),
//     ),
// ))
// ->printR();
exit();