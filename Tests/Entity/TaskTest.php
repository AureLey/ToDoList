<?php

declare(strict_types=1);

/*
 * This file is part of Todolist
 *
 * (c)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
