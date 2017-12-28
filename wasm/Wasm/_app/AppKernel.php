<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Wasm\UtilBundle\Debug\Debug;

function cl($var) { Debug::cl($var); }
function cli($var) { Debug::cliDump($var); };

// Other Apps extend this Kernel?
//     -> Can override registerBundles with $extraBundles
class AppKernel extends Kernel
{
    public function __construct($env, $debug)
    {
        parent::__construct($env, $debug);
    }

    public function registerBundles($extraBundles = array())
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            new Bazinga\Bundle\FakerBundle\BazingaFakerBundle(),
            new KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle(),

            new Wasm\ApiBundle\WasmApiBundle(),
            new Wasm\AppBundle\WasmAppBundle(),
            new Wasm\AppSceneBundle\WasmAppSceneBundle(),
            new Wasm\BootBundle\WasmBootBundle(),
            new Wasm\CmfBundle\WasmCmfBundle(),
            new Wasm\FormBundle\WasmFormBundle(),
            new Wasm\FsBundle\WasmFsBundle(),
            new Wasm\ModBundle\WasmModBundle(),
            new Wasm\ModRenderBundle\WasmModRenderBundle(),
            new Wasm\StoreBundle\WasmStoreBundle(),
            new Wasm\UserBundle\WasmUserBundle(),
            new Wasm\UtilBundle\WasmUtilBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            //$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return $this->getRootDir().'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return $this->getRootDir().'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
