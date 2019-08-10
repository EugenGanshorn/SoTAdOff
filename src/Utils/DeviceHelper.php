<?php

namespace App\Utils;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;
use TasmotaHttpClient\Request;
use TasmotaHttpClient\Url;

class DeviceHelper implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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

    /**
     * @var bool
     */
    protected $inBulk;

    public function getStatus(array $options = [], \Closure $callback = null): array
    {
        $this->prepareRequest();

        return $this->request->Status(0, $options, $callback);
    }

    public function toggle(): void
    {
        $this->prepareRequest();
        $this->request->Power(2, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
    }

    public function wakeup(?int $duration = 3600, ?int $wakeup = 100): void
    {
        $this->prepareRequest();
        $this->request->WakeupDuration($duration, [], function () use ($wakeup) {
            $this->request->Wakeup($wakeup, [], function () {
                $this->updateStatus();
            });
        });
        $this->finishRequest();
    }

    public function setColor(string $color): void
    {
        $this->prepareRequest();
        $this->request->Color($color, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
    }

    public function setTemperature(int $temperature): void
    {
        $this->prepareRequest();
        $this->request->CT($temperature, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
    }

    public function setDimmer(int $dimmer): void
    {
        $this->prepareRequest();
        $this->request->Dimmer($dimmer, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
    }

    public function setFade(bool $fade): void
    {
        $this->prepareRequest();
        $this->request->Fade($fade, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
    }

    public function setSpeed(int $speed): void
    {
        $this->prepareRequest();
        $this->request->Speed($speed, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
    }

    public function setScheme(int $scheme): void
    {
        $this->prepareRequest();
        $this->request->Scheme($scheme, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
    }

    public function setLedTable(bool $ledTable): void
    {
        $this->prepareRequest();
        $this->request->LedTable($ledTable, [], function () {
            $this->updateStatus();
        });
        $this->finishRequest();
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

    public function updateStatus(int $timeout = 5, bool $finishRequest = true): void
    {
        $this->getStatus(['timeout' => $timeout], function (array $status) {
            $this->persistStatus($status);
        });

        $finishRequest && $this->finishRequest();
    }

    public function isExists(int $timeout = 5, \Closure $callback = null): bool
    {
        try {
            $this->getStatus(['timeout' => $timeout], $callback);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function doUpgrade(string $otaUrl, int $timeout = 180): bool
    {
        $this->logger->info('set new ota url: {otaUrl} ...', ['otaUrl' => $otaUrl]);
        $this->setOtaUrl($otaUrl);
        $this->logger->info('...done');

        $this->logger->info('upgrade...');
        $this->upgrade();
        $this->logger->info('...sleep for 60sec');

        sleep(60);

        $start = microtime(true);
        do {
            usleep(250000);

            if ($start + $timeout < microtime(true)) {
                $this->logger->info('device is not reachable');
                return false;
            }

            $this->logger->info('is device reachable?');
        } while (!$this->isExists());

        $this->logger->info('yes it is!');

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

        $this->device = $this->entityManager->merge($this->device);
        $this->entityManager->flush();
    }

    public function startBulk()
    {
        $this->inBulk = true;
        $this->request->startAsyncRequests();
    }

    public function finishBulk()
    {
        $this->request->finishAsyncRquests();
        $this->inBulk = false;
    }

    protected function finishRequest()
    {
        if (!$this->inBulk) {
            $this->request->finishAsyncRquests();
        }
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
