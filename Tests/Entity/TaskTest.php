<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    /**
     * testTaskisDone, testing function who change toggle in one task.
     */
    public function testTaskisDone(): void
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertTrue($task->isDone());
    }
}
