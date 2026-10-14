<?php

namespace Osd84\DiscordBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Alertes Discord par webhook.
 *
 *   # config/packages/osd84_discord.yaml
 *   osd84_discord:
 *       webhook: '%env(DISCORD_WEBHOOK)%'    # vide = aucune alerte envoyee
 *
 * Service : Osd84\DiscordBundle\Discord (autowirable), methode send(string $message): bool.
 */
class Osd84DiscordBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('webhook')
                    ->info('URL du webhook Discord ; vide = on n\'envoie rien')
                    ->defaultValue('')
                ->end()
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->services()
            ->set(Discord::class)
                ->args([$config['webhook']]);
    }
}
