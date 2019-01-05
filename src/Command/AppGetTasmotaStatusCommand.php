<?php

namespace App\Command;

use App\Repository\DeviceRepository;
use App\Utils\CommandHelper;
use App\Utils\DeviceHelper;
use App\Utils\ProcessManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class AppGetTasmotaStatusCommand extends ContainerAwareCommand
{
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startProcesses = false;
        $ipAddress = $input->getOption('device');
        if ($input->hasOption('device') && null !== $ipAddress) {
            /** @noinspection PhpUndefinedMethodInspection */
            $devices = [$this->deviceRespository->findOneByIpAddress($ipAddress)];
        } else {
            $devices = $this->deviceRespository->findAll();
            $startProcesses = true;
        }

        foreach ($devices as $device) {
            if ($startProcesses) {
                $this->startProcess($input, $device->getIpAddress());
                continue;
            }

            $this->deviceHelper
                ->setDevice($device)
                ->updateStatus()
            ;
        }

        $this->processManager->waitForProcesses();
    }

    /**
     * @param $input
     * @param $ipAddress
     *
     * @return Process
     */
    protected function startProcess(InputInterface $input, string $ipAddress): Process
    {
        $commandName = $this->getName();
        $newInput = clone $input;
        $newInput->setOption('device', $ipAddress);

        $process = $this->processManager->createNewProcess($this->commandHelper->buildCommand($commandName, $newInput));
        $process->start(
            function ($type, $buffer) {
                echo $buffer;
            }
        );

        return $process;
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
     * @param ProcessManager $processManager
     *
     * @return AppGetTasmotaStatusCommand
     */
    public function setProcessManager(ProcessManager $processManager): AppGetTasmotaStatusCommand
    {
        $this->processManager = $processManager;

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
