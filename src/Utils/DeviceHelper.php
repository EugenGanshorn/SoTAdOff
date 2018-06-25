<?php

namespace App\Utils;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
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
        $config = [
            'Status' => [
                'methodNamePrefix' => '',
                'data' => &$status['Status'],
            ],
            'StatusFWR' => [
                'methodNamePrefix' => 'Fwr',
                'data' => &$status['StatusFWR'],
            ],
            'StatusPRM' => [
                'methodNamePrefix' => 'Prm',
                'data' => &$status['StatusPRM'],
            ],
            'StatusLOG' => [
                'methodNamePrefix' => 'Log',
                'data' => &$status['StatusLOG'],
            ],
            'StatusNET' => [
                'methodNamePrefix' => 'Net',
                'data' => &$status['StatusNET'],
            ],
            'StatusMQT' => [
                'methodNamePrefix' => '',
                'data' => &$status['StatusMQT'],
            ],
            'StatusSTS' => [
                'methodNamePrefix' => 'Sts',
                'data' => &$status['StatusSTS'],
            ],
            'StatusSTSWifi' => [
                'methodNamePrefix' => 'Wifi',
                'data' => &$status['StatusSTS']['Wifi'],
            ],
        ];

        foreach ($config as $statusConfig) {
            foreach ($statusConfig['data'] as $name => $value) {
                if (!is_scalar($value)) {
                    continue;
                }

                $methodName = sprintf('set%s%s', $statusConfig['methodNamePrefix'], $name);
                if (method_exists($this->device, $methodName)) {
                    $this->device->$methodName($value);
                }
            }
        }

        $this->entityManager->persist($this->device);
        $this->entityManager->flush();
    }

    public function getStatus(): array
    {
        $this->request->getUrl()->setIpAddress($this->device->getIpAddress());

        $status = $this->request->Status(0);

        // convert friendly name to string
        $status['Status']['FriendlyName'] = implode(' ', $status['Status']['FriendlyName']);

        return $status;
    }

    public function toogle(): void
    {
        $this->request->getUrl()->setIpAddress($this->device->getIpAddress());

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
}
