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

    public function getStatus(array $options = []): array
    {
        $this->prepareRequest();

        return $this->request->Status(0, $options);
    }

    public function toggle(): void
    {
        $this->prepareRequest();
        $this->request->Power(2);
        $this->updateStatus();
    }

    public function wakeup(?int $duration = 3600, ?int $wakeup = 100): void
    {
        $this->prepareRequest();
        $this->request->WakeupDuration($duration);
        $this->request->Wakeup($wakeup);
        $this->updateStatus();
    }

    public function setColor(string $color): void
    {
        $this->prepareRequest();
        $this->request->Color($color);
        $this->updateStatus();
    }

    public function setTemperature(int $temperature): void
    {
        $this->prepareRequest();
        $this->request->CT($temperature);
        $this->updateStatus();
    }

    public function setDimmer(int $dimmer): void
    {
        $this->prepareRequest();
        $this->request->Dimmer($dimmer);
        $this->updateStatus();
    }

    public function setFade(bool $fade): void
    {
        $this->prepareRequest();
        $this->request->Fade($fade);
        $this->updateStatus();
    }

    public function setSpeed(int $speed): void
    {
        $this->prepareRequest();
        $this->request->Speed($speed);
        $this->updateStatus();
    }

    public function setScheme(int $scheme): void
    {
        $this->prepareRequest();
        $this->request->Scheme($scheme);
        $this->updateStatus();
    }

    public function setLedTable(bool $ledTable): void
    {
        $this->prepareRequest();
        $this->request->LedTable($ledTable);
        $this->updateStatus();
    }

    public function setOtaUrl(string $otaUrl): void
    {
        $this->prepareRequest();
        $this->request->OtaUrl($otaUrl);
    }

    public function upgrade(): void
    {
        $this->prepareRequest();
        $this->request->Upgrade(1);
    }

    public function updateStatus(int $timeout = 5): void
    {
        $status = $this->getStatus(['timeout' => $timeout]);
        $this->persistStatus($status);
    }

    public function isExists(int $timeout = 1): bool
    {
        try {
            $this->getStatus(['timeout' => $timeout]);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function doUpgrade(string $otaUrl, int $timeout = 180): bool
    {
        $this->setOtaUrl($otaUrl);
        $this->upgrade();

        sleep(60);

        $start = microtime(true);
        do {
            usleep(250000);

            if ($start + $timeout < microtime(true)) {
                return false;
            }
        } while (!$this->isExists());

        return true;
    }

    public function persistStatus(array $status): void
    {
        $parsedStatus = $this->parseStatus($status);
        $this->serializer->deserialize(
            json_encode($parsedStatus),
            Device::class,
            'json',
            ['object_to_populate' => $this->device]
        );

        if (null === $this->device->getName()) {
            $this->device->setName($parsedStatus['FriendlyName']);
        }

        $this->entityManager->persist($this->device);
        $this->entityManager->flush();
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
}
