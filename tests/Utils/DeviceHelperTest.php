<?php

namespace App\Tests\Utils;

use App\Entity\Device;
use App\Utils\DeviceHelper;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use TasmotaHttpClient\Request;
use TasmotaHttpClient\Url;

class DeviceHelperTest extends TestCase
{
    public function testGetStatus(): void
    {
        $device = $this->getMockBuilder(Device::class)
            ->getMock()
        ;

        $device->expects($this->once())
            ->method('getIpAddress')
            ->willReturn('localhost')
        ;

        $url = $this->getMockBuilder(Url::class)
            ->getMock()
        ;

        $url->expects($this->once())
            ->method('setIpAddress')
            ->with('localhost')
            ->willReturnSelf()
        ;

        $request = $this->getMockBuilder(Request::class)
            ->getMock()
        ;

        $request->expects($this->once())
            ->method('getUrl')
            ->willReturn($url)
        ;

        $request->expects($this->once())
            ->method('__call')
            ->with('Status', [0])
            ->willReturn(['foo' => 'bar'])
        ;

        $sut = new DeviceHelper();
        $sut->setDevice($device);
        $sut->setUrl($url);
        $sut->setRequest($request);

        $this->assertEquals(['foo' => 'bar'], $sut->getStatus());
    }

    public function testToggle(): void
    {
        $device = $this->getMockBuilder(Device::class)
            ->getMock()
        ;

        $device->expects($this->exactly(2))
            ->method('getIpAddress')
            ->willReturn('localhost')
        ;

        $url = $this->getMockBuilder(Url::class)
            ->getMock()
        ;

        $url->expects($this->exactly(2))
            ->method('setIpAddress')
            ->with('localhost')
            ->willReturnSelf()
        ;

        $request = $this->getMockBuilder(Request::class)
            ->getMock()
        ;

        $request->expects($this->exactly(2))
            ->method('getUrl')
            ->willReturn($url)
        ;

        $request->expects($this->exactly(2))
            ->method('__call')
            ->withConsecutive(
                ['Power', [2]],
                ['Status', [0]]
            )
            ->will($this->onConsecutiveCalls(
                ['foo' => 'bar'],
                [
                    'Status'    => ['FriendlyName' => []],
                    'StatusSTS' => [
                        'Wifi' => [],
                    ],
                    'StatusMQT' => [],
                    'StatusNET' => [],
                    'StatusLOG' => [],
                    'StatusPRM' => [],
                    'StatusFWR' => [],
                ]
            ))
        ;

        $sut = new DeviceHelper();
        $sut->setDevice($device);
        $sut->setEntityManager($this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock());
        $sut->setSerializer($this->getMockBuilder(SerializerInterface::class)->getMock());
        $sut->setUrl($url);
        $sut->setRequest($request);

        $this->assertNull($sut->toggle());
    }
}
