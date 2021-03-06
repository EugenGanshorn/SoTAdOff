<?php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;
use TasmotaHttpClient\Request;

class DeviceHelperFactory implements LoggerAwareInterface
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
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function create(): DeviceHelper
    {
        $deviceHelper = new DeviceHelper();
        $deviceHelper
            ->setSerializer($this->serializer)
            ->setClient($this->client)
            ->setEntityManager($this->entityManager)
            ->setRequest($this->request)
            ->setLogger($this->logger)
        ;

        return $deviceHelper;
    }

    /**
     * @required
     *
     * @param Client $client
     *
     * @return DeviceHelperFactory
     */
    public function setClient(Client $client): DeviceHelperFactory
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @required
     *
     * @param Request $request
     *
     * @return DeviceHelperFactory
     */
    public function setRequest(Request $request): DeviceHelperFactory
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @required
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return DeviceHelperFactory
     */
    public function setEntityManager(EntityManagerInterface $entityManager): DeviceHelperFactory
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @required
     *
     * @param SerializerInterface $serializer
     *
     * @return DeviceHelperFactory
     */
    public function setSerializer(SerializerInterface $serializer): DeviceHelperFactory
    {
        $this->serializer = $serializer;

        return $this;
    }
}
