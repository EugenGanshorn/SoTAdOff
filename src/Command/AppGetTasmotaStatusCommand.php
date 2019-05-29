<?php

namespace App\Command;

use App\Repository\DeviceRepository;
use App\Utils\CommandHelper;
use App\Utils\DeviceHelper;
use App\Utils\ProcessManager;
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
     * @var DeviceHelper
     */
    protected $deviceHelper;

    /**
     * @var DeviceRepository
     */
    protected $deviceRespository;

    /**
     * @var ProcessManager
     */
    protected $processManager;

    /**
     * @var CommandHelper
     */
    protected $commandHelper;

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

        $this->deviceHelper->startBulk();

        foreach ($devices as $device) {
                $this->deviceHelper
                    ->setDevice($device)
                    ->updateStatus($input->getOption('timeout'));
        }

        $this->deviceHelper->finishBulk();
    }

    /**
     * @required
     *
     * @param DeviceHelper $deviceHelper
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setDeviceHelper(DeviceHelper $deviceHelper): AppGetTasmotaStatusCommand
    {
        $this->deviceHelper = $deviceHelper;

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

    /**
     * @required
     *
     * @param CommandHelper $commandHelper
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setCommandHelper(CommandHelper $commandHelper): AppGetTasmotaStatusCommand
    {
        $this->commandHelper = $commandHelper;

        return $this;
    }
}
