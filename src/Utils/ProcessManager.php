<?php

namespace App\Utils;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

class ProcessManager
{
    /**
     * @var Process[]
     */
    protected $processes = [];

    /**
     * @var int
     */
    protected $concurrentProcesses = 6;

    public function __construct(int $concurrentProcesses = 6)
    {
        $this->concurrentProcesses = $concurrentProcesses;
    }

    /**
     * @param string         $commandline
     * @param string|null    $cwd
     * @param array|null     $env
     * @param InputInterface $input
     * @param float|null     $timeout
     *
     * @return Process
     */
    public function createNewProcess($commandline, string $cwd = null, array $env = null, $input = null, ?float $timeout = 60): Process
    {
        $this->concurrentCheck();

        $this->processes[] = $process = new Process($commandline, $cwd, $env, $input, $timeout);

        return $process;
    }

    public function waitForProcesses(): void
    {
        foreach ($this->processes as $id => $process) {
            $process->wait();
            unset($this->processes[$id]);
        }
    }

    protected function concurrentCheck(): void
    {
        while (\count($this->processes) > $this->concurrentProcesses) {
            $this->waitForProcesses();
        }
    }
}
