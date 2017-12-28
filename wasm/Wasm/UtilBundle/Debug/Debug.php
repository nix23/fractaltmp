<?php
namespace Wasm\UtilBundle\Debug;

use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class Debug 
{
    // @todo -> Check and merge with Symfony debugger 
    //       -> All VDumps should be available on separate tab;
    public static function cliDump($var) {
        $output = fopen("php://memory", "r+b");
        $cloner = new VarCloner();
        $dumper = new CliDumper();

        $dumper->dump(
            $cloner->cloneVar($var),
            $output
        );
        $output = stream_get_contents($output, -1, 0);
        var_dump($output);
        //VarDumper::dump($output);
    }

    public static function cl($var) {
        //echo "<pre>";
        self::cliDump($var);
        //echo "</pre>";
        exit();
    }

    public static function clinput() {
        cl(file_get_contents('php://input'));
    }

    // @todo -> Fix this
    public static function fl($var) {
        ob_start();
        //var_dump($var);
        cliDump($var);
        $result = ob_get_clean();

        file_put_contents(
            "/var/www/fabalist/var/logs/var_dump.html", 
            $result, 
            FILE_APPEND
        );
    }
}