<?php

namespace App\Command;

use App\Repository\DeviceRepository;
use App\Utils\DeviceHelper;
use App\Utils\DeviceHelperFactory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppGetTasmotaStatusCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected static $defaultName = 'app:get-tasmota-status';

    /**
     * @var DeviceHelperFactory
     */
    protected $deviceHelperFactory;

    /**
     * @var DeviceRepository
     */
    protected $deviceRespository;

    protected function configure()
    {
        $this
            ->setDescription('get tasmota status from all devices')
            ->addOption('device', 'd', InputArgument::OPTIONAL, 'ip address')
            ->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, 'timeout', 5)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ipAddress = $input->getOption('device');
        if ($input->hasOption('device') && null !== $ipAddress) {
            /** @noinspection PhpUndefinedMethodInspection */
            $devices = [$this->deviceRespository->findOneByIpAddress($ipAddress)];
        } else {
            $devices = $this->deviceRespository->findAll();
        }

        $deviceHelpers = [];
        foreach ($devices as $device) {
            $deviceHelpers[] = $deviceHelper = $this->deviceHelperFactory->create();
            $deviceHelper->startBulk();
            $deviceHelper
                ->setDevice($device)
                ->updateStatus($input->getOption('timeout'));
        }

        foreach ($deviceHelpers as $deviceHelper) {
            $deviceHelper->finishBulk();
        }
    }

    /**
     * @required
     *
     * @param DeviceHelperFactory $deviceHelperFactory
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setDeviceHelperFactory(DeviceHelperFactory $deviceHelperFactory): AppGetTasmotaStatusCommand
    {
        $this->deviceHelperFactory = $deviceHelperFactory;

        return $this;
    }

    /**
     * @required
     *
     * @param DeviceRepository $deviceRespository
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setDeviceRespository(DeviceRepository $deviceRespository): AppGetTasmotaStatusCommand
    {
        $this->deviceRespository = $deviceRespository;

        return $this;
    }
}
