<?php

namespace App\Utils;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\SerializerInterface;
use TasmotaHttpClient\Request;
use TasmotaHttpClient\Url;

class DeviceHelper
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var Device
     */
    protected $device;

    public function updateStatus(): void
    {
        $status = $this->getStatus();
        $this->persistStatus($status);
    }

    public function persistStatus(array $status): void
    {
        $this->serializer->deserialize(
            json_encode($this->parseStatus($status)),
            Device::class,
            'json',
            ['object_to_populate' => $this->device]
        );

        $this->entityManager->persist($this->device);
        $this->entityManager->flush();
    }

    public function getStatus(): array
    {
        $this->prepareRequest();
        return $this->request->Status(0);
    }

    public function toggle(): void
    {
        $this->prepareRequest();
        $this->request->Power(2);
        $this->updateStatus();
    }

    /**
     * @required
     *
     * @param Client $client
     *
     * @return DeviceHelper
     */
    public function setClient(Client $client): DeviceHelper
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @required
     *
     * @param Request $request
     *
     * @return DeviceHelper
     */
    public function setRequest(Request $request): DeviceHelper
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @required
     *
     * @param Url $url
     *
     * @return DeviceHelper
     */
    public function setUrl(Url $url): DeviceHelper
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param Device $device
     *
     * @return DeviceHelper
     */
    public function setDevice(Device $device): DeviceHelper
    {
        $this->device = $device;

        return $this;
    }

    /**
     * @required
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return DeviceHelper
     */
    public function setEntityManager(EntityManagerInterface $entityManager): DeviceHelper
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @required
     *
     * @param SerializerInterface $serializer
     *
     * @return DeviceHelper
     */
    public function setSerializer(SerializerInterface $serializer): DeviceHelper
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @param array  $inputArray
     * @param string $prefix
     *
     * @return array
     */
    protected function prefixArrayKeys(array $inputArray, string $prefix): array
    {
        return array_combine(
            preg_filter('/^/', $prefix, array_keys($inputArray)),
            array_values($inputArray)
        );
    }

    /**
     * @param array $status
     *
     * @return array
     */
    protected function parseStatus(array $status): array
    {
        // convert friendly name to string
        $status['Status']['FriendlyName'] = implode(' ', $status['Status']['FriendlyName']);

        // remove unsed keys
        unset($status['StatusLOG']['SSId'], $status['StatusLOG']['SetOption']);

        $parsedStatus = [];
        $parsedStatus = array_merge($parsedStatus, $this->prefixArrayKeys($status['StatusSTS']['Wifi'], 'Wifi'));

        // remove "duplicate" keys
        unset($status['StatusSTS']['Wifi']);

        $parsedStatus = array_merge($parsedStatus, $this->prefixArrayKeys($status['StatusSTS'], 'Sts'));
        $parsedStatus = array_merge($parsedStatus, $status['StatusMQT']);
        $parsedStatus = array_merge($parsedStatus, $this->prefixArrayKeys($status['StatusNET'], 'Net'));
        $parsedStatus = array_merge($parsedStatus, $this->prefixArrayKeys($status['StatusLOG'], 'Log'));
        $parsedStatus = array_merge($parsedStatus, $this->prefixArrayKeys($status['StatusPRM'], 'Prm'));
        $parsedStatus = array_merge($parsedStatus, $this->prefixArrayKeys($status['StatusFWR'], 'Fwr'));
        $parsedStatus = array_merge($parsedStatus, $status['Status']);

        return $parsedStatus;
    }

    /**
     * @return Url
     */
    protected function prepareRequest(): Url
    {
        return $this->request->getUrl()->setIpAddress($this->device->getIpAddress());
    }
}
