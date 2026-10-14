<?php

namespace Osd84\DiscordBundle\Tests;

use Osd84\DiscordBundle\Discord;
use PHPUnit\Framework\TestCase;

class DiscordTest extends TestCase
{
    public function testWebhookVideNEnvoieRien(): void
    {
        $discord = new Discord('');
        $this->assertFalse($discord->estActif());
        $this->assertFalse($discord->send('bonjour'));
    }

    public function testWebhookInjoignableRendFalseSansException(): void
    {
        // port ferme : curl echoue tout de suite, l'appli continue
        $discord = new Discord('http://127.0.0.1:9/webhook');
        $this->assertTrue($discord->estActif());
        $this->assertFalse($discord->send('bonjour'));
    }

    public function testLeBundleFournitLeService(): void
    {
        $kernel = new TestKernel(['webhook' => 'http://127.0.0.1:9/webhook']);
        $kernel->boot();
        $discord = $kernel->getContainer()->get('test.discord');
        $this->assertInstanceOf(Discord::class, $discord);
        $this->assertTrue($discord->estActif());
        $kernel->shutdown();
    }

    public function testConfigParDefautSansWebhook(): void
    {
        $kernel = new TestKernel();
        $kernel->boot();
        $this->assertFalse($kernel->getContainer()->get('test.discord')->estActif());
        $kernel->shutdown();
    }
}
