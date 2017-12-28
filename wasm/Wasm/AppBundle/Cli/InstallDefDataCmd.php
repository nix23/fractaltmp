<?php
namespace Wasm\AppBundle\Cli;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class InstallDefDataCmd extends ContainerAwareCommand
{
    private $container;
    private $output;

    public function __construct($container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('wasm:installdata')
            ->setDescription('Installs fractal demo groups and apps.');
    }

    private function get($service)
    {
        return $this->container->get($service);
    }

    private function execCmd($cmdName, $args = array())
    {
        $cmd = $this->getApplication()->find($cmdName);
        $cmdInput = new ArrayInput($args);

        return $cmd->run($cmdInput, $this->output);
    }

    // @todo -> Rm on prod
    private function recreateWasmDb()
    {
        $this->execCmd("doctrine:database:drop", array("--force" => true));
        $this->execCmd("doctrine:database:create");
        $this->execCmd("doctrine:schema:create");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->recreateWasmDb();

        $this
            ->get("Wasm.Mod.Cmd.InstallModCmd")
            ->execDefaultMods();
        $this
            ->get("Wasm.AppScene.Cmd.CreateBreakpointsCmd")
            ->exec();
        $this
            ->get("Wasm.AppScene.Cmd.CreateLayoutCmd")
            ->exec();
        $this
            ->get("Wasm.App.Cmd.CreateGroupCmd")
            ->execDefaultGroup();
        $this
            ->get("Wasm.App.Cmd.CreateAppCmd")
            ->execDemoApps();
        $this
            ->get("Wasm.AppScene.Cmd.CreateSceneCmd")
            ->execDemoScenes();
        $this
            ->get("Wasm.AppScene.Cmd.AddSceneLayoutCmd")
            ->execDemoScenes();
        $this
            ->get("Wasm.AppScene.Cmd.AddLayoutModInstanceCmd")
            ->execDemoScenes();
    }
}