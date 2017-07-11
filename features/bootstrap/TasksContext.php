<?php

namespace features\bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;

class TasksContext extends RawMinkContext
{
    const TASKNAME = 'Shiny new task';

    public function __construct()
    {
        //
    }
    
    /**
     * @When I provide the task title
     */
    public function iProvideTheTaskTitle()
    {
        $page = $this->getSession()->getPage();

        $page->fillField('task-name', static::TASKNAME);
    }

    /**
     * @When save the task
     */
    public function saveTheTask()
    {
        $el = $this->getSession()->getPage()->find('css', 'form button[type="submit"]');

        $el->press();
    }

    /**
     * @Then I should see this task in the current tasks list
     */
    public function iShouldSeeThisTaskInTheCurrentTasksList()
    {
        $tasks = $this->getSession()->getPage()->findAll('css', '.task-table .table-text');

        foreach ($tasks as $task) {
            if ($task->getText() === static::TASKNAME) {
                return;
            }
        }
        
        throw new \Exception('Your new task wans\'t found!');
    }
}
