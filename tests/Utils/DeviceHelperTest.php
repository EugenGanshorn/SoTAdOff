<?php

namespace App\Tests\Utils;

use App\Entity\Device;
use App\Utils\DeviceHelper;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
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
            ->with('Status', [0, ['timeout' => 42], null])
            ->willReturn(['foo' => 'bar'])
        ;

        $sut = new DeviceHelper();
        $sut->setDevice($device);
        $sut->setUrl($url);
        $sut->setRequest($request);

        $this->assertEquals(['foo' => 'bar'], $sut->getStatus(['timeout' => 42]));
    }

    public function testToggle(): void
    {
        $device = $this->getMockBuilder(Device::class)
            ->getMock()
        ;

        $device->expects($this->exactly(1))
            ->method('getIpAddress')
            ->willReturn('localhost')
        ;

        $url = $this->getMockBuilder(Url::class)
            ->getMock()
        ;

        $url->expects($this->exactly(1))
            ->method('setIpAddress')
            ->with('localhost')
            ->willReturnSelf()
        ;

        $request = $this->getMockBuilder(Request::class)
            ->getMock()
        ;

        $request->expects($this->exactly(1))
            ->method('getUrl')
            ->willReturn($url)
        ;

        $request->expects($this->exactly(1))
            ->method('__call')
            ->withConsecutive(
                ['Power', [2, [], function () {}]],
            )
            ->will($this->onConsecutiveCalls(
                ['foo' => 'bar'],
            ))
        ;

        $sut = new DeviceHelper();
        $sut->setDevice($device);
        $sut->setEntityManager($this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock());
        $sut->setSerializer($this->getMockBuilder(SerializerInterface::class)->getMock());
        $sut->setUrl($url);
        $sut->setRequest($request);

        $sut->toggle();
    }
}
