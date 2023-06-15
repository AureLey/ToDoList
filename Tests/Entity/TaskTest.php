<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskisDone(): void
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertTrue($task->isDone());
    }
}
