<?php

namespace Osd84\DiscordBundle\Tests;

use Osd84\DiscordBundle\Osd84DiscordBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;

/** Un noyau minimal pour charger le bundle avec une config donnee. */
class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct(private array $configDiscord = [])
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [new FrameworkBundle(), new Osd84DiscordBundle()];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', ['test' => true, 'secret' => 'test', 'http_method_override' => false, 'handle_all_throwables' => true, 'php_errors' => ['log' => true]]);
        $container->extension('osd84_discord', $this->configDiscord);
        // le service est prive : on le rend visible au conteneur de test
        $container->services()->alias('test.discord', \Osd84\DiscordBundle\Discord::class)->public();
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/osd84_discord_bundle/' . md5(serialize($this->configDiscord)) . '/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/osd84_discord_bundle/log';
    }
}
