<?php

namespace App\Utils;

use Symfony\Component\Console\Input\InputInterface;

class CommandHelper
{
    /**
     * @param InputInterface $input
     *
     * @return array
     */
    public function createCommandOptions(InputInterface $input): array
    {
        $options = [];
        foreach ($input->getOptions() as $option => $value) {
            if (false === $value || (null === $value && !$input->hasOption($option))) {
                continue;
            }

            if (null === $value) {
                $options[] = sprintf('--%s', $option);
            } else {
                $options[] = sprintf('--%s=%s', $option, $value);
            }
        }

        return $options;
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     */
    public function createCommandArguments(InputInterface $input): array
    {
        $arguments = $input->getArguments();
        array_shift($arguments);

        return $arguments;
    }

    /**
     * @param string         $commandName
     * @param InputInterface $input
     *
     * @return string
     */
    public function buildCommand(string $commandName, InputInterface $input): string
    {
        return sprintf(
            'timeout 30s bin/console %s %s %s',
            $commandName,
            implode(' ', $this->createCommandArguments($input)),
            implode(' ', $this->createCommandOptions($input))
        );
    }
}
