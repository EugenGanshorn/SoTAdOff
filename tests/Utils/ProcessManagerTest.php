<?php

namespace App\Tests\Utils;

use App\Utils\ProcessManager;
use PHPUnit\Framework\TestCase;

class ProcessManagerTest extends TestCase
{
    public function testProcessCommandLine(): void
    {
        $sut = new ProcessManager();

        $this->assertEquals('test', $sut->createNewProcess('test')->getCommandLine());
    }
}
