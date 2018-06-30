<?php

namespace App\Tests\Utils;

use App\Utils\GithubHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class GithubHelperTest extends TestCase
{
    public function testGetFilePath(): void
    {
        $sut = new GithubHelper();
        $sut->setProjectDir('test');

        $this->assertEquals('test/var/firmware/asset.name', $sut->getFilePath('asset.name'));
    }

    public function testGetLatest(): void
    {
        $client = $this->getMockBuilder(Client::class)
            ->getMock()
        ;

        $client->expects($this->once())
            ->method('__call')
            ->with('get', ['https://api.github.com/repos/arendst/Sonoff-Tasmota/releases/latest'])
            ->willReturn(new Response(200, [], '{}'))
        ;

        $sut = new GithubHelper();
        $sut->setClient($client);
        $sut->getLatest();
    }

    public function testDownloadFirmware(): void
    {
        $client = $this->getMockBuilder(Client::class)
            ->getMock()
        ;

        $client->expects($this->exactly(3))
            ->method('__call')
            ->withConsecutive(
                ['get', ['https://api.github.com/repos/arendst/Sonoff-Tasmota/releases/latest']],
                ['get', ['http://www.google.de', ['sink' => '/var/firmware/sonoff-DE.bin']]],
                ['get', ['http://www.google.com', ['sink' => '/var/firmware/sonoff-minimal.bin']]]
            )
            ->willReturn(new Response(200, [], '{"assets": [{"name": "sonoff-DE.bin", "browser_download_url": "http://www.google.de"},{"name": "sonoff-minimal.bin", "browser_download_url": "http://www.google.com"}]}'))
        ;

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->getMock()
        ;

        $sut = new GithubHelper();
        $sut->setClient($client);
        $sut->setFilesystem($filesystem);
        $sut->downloadFirmware('DE');
    }
}
