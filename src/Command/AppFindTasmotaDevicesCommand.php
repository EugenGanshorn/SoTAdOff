<?php

namespace App\Command;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Utils\CommandHelper;
use App\Utils\DeviceHelper;
use App\Utils\ProcessManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class AppFindTasmotaDevicesCommand extends Command
{
    protected static $defaultName = 'app:find-tasmota-devices';

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
            ->setDescription('find all tasmota devices in your local network')
            ->addArgument('from', InputArgument::REQUIRED, 'first ip address')
            ->addArgument('to', InputArgument::REQUIRED, 'last ip address')
            ->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, 'timeout', 1)
            ->addOption('ignore-exists', 'i', InputOption::VALUE_OPTIONAL, 'ignore already exists devices', true)
            ->addOption('auto-create', 'c', InputOption::VALUE_OPTIONAL, 'create automatically new founded devices', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $from = ip2long($input->getArgument('from'));
        $to = ip2long($input->getArgument('to'));
        $ignoreExists = $input->getOption('ignore-exists');
        $autoCreate = $input->hasOption('auto-create');

        if ($to < $from) {
            $toOriginal = $to;
            $to = $from;
            $from = $toOriginal;
            unset($toOriginal);
        }

        $createProcesses = false;
        if ($to - $from > 1) {
            $createProcesses = true;
        }

        $amountNewDevices = 0;
        for ($i = $from; $i <= $to; ++$i) {
            $ipAddress = long2ip($i);

            if ($createProcesses) {
                $this->startProcess($input, $ipAddress);
                continue;
            }

            /* @noinspection PhpUndefinedMethodInspection */
            if ($ignoreExists && !empty($this->deviceRespository->findOneByIpAddress($ipAddress))) {
                $io->note(sprintf('device %s already exists', $ipAddress));
                continue;
            }

            $io->note(sprintf('scan %s', $ipAddress));

            $this->deviceHelper->setDevice($this->createNewDevice($ipAddress, $i));
            $isExists = $this->deviceHelper->isExists($input->getOption('timeout'));
            if ($isExists) {
                $io->success(sprintf('new tasmota device found at %s', $ipAddress));
                ++$amountNewDevices;

                if ($autoCreate) {
                    $this->deviceHelper->updateStatus();
                }
            }
        }

        $this->processManager->waitForProcesses();

        if (!$createProcesses) {
            if ($amountNewDevices) {
                $io->success(sprintf('Found %d new devices', $amountNewDevices));
            } else {
                $io->error('No new devices found');
            }
        }
    }

    /**
     * @param string $ipAddress
     * @param int    $position
     *
     * @return Device
     */
    protected function createNewDevice(string $ipAddress, int $position): Device
    {
        $device = new Device();
        $device->setVisible(1);
        $device->setIpAddress($ipAddress);
        $device->setPosition($position);
        $device->setVisible(true);

        return $device;
    }

    /**
     * @param InputInterface $input
     * @param string         $ipAddress
     *
     * @return Process
     */
    protected function startProcess(InputInterface $input, string $ipAddress): Process
    {
        $commandName = $this->getName();

        $newInput = clone $input;
        $newInput->setArgument('from', $ipAddress);
        $newInput->setArgument('to', $ipAddress);

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
     * @return AppFindTasmotaDevicesCommand
     */
    public function setDeviceHelper(DeviceHelper $deviceHelper): AppFindTasmotaDevicesCommand
    {
        $this->deviceHelper = $deviceHelper;

        return $this;
    }

    /**
     * @required
     *
     * @param DeviceRepository $deviceRespository
     *
     * @return AppFindTasmotaDevicesCommand
     */
    public function setDeviceRespository(DeviceRepository $deviceRespository): AppFindTasmotaDevicesCommand
    {
        $this->deviceRespository = $deviceRespository;

        return $this;
    }

    /**
     * @required
     *
     * @param ProcessManager $processManager
     *
     * @return AppFindTasmotaDevicesCommand
     */
    public function setProcessManager(ProcessManager $processManager): AppFindTasmotaDevicesCommand
    {
        $this->processManager = $processManager;

        return $this;
    }

    /**
     * @required
     *
     * @param CommandHelper $commandHelper
     *
     * @return AppFindTasmotaDevicesCommand
     */
    public function setCommandHelper(CommandHelper $commandHelper): AppFindTasmotaDevicesCommand
    {
        $this->commandHelper = $commandHelper;

        return $this;
    }
}
