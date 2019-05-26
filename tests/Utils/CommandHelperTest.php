<?php

namespace App\Tests\Utils;

use App\Utils\CommandHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class CommandHelperTest extends TestCase
{
    public function testBuildCommand(): void
    {
        $sut = new CommandHelper();

        $definition = new InputDefinition([
            new InputArgument('name1', InputArgument::REQUIRED),
            new InputArgument('name2', InputArgument::REQUIRED),
            new InputOption('foo', 'f', InputOption::VALUE_REQUIRED),
        ]);

        $input = new ArrayInput(['--foo' => 'bar', 'name1' => 'test', 'name2' => 'foo'], $definition);
        $this->assertEquals('timeout 30s bin/console test:foo:bar foo --foo=bar', $sut->buildCommand('test:foo:bar', $input));
    }
}
