<?php

namespace features\bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;

class ApplicationStateContext extends RawMinkContext
{
    /**
     * @AfterScenario
     */
    public function clearTasks()
    {
        $this->visitPath('/');

        $page = $this->getSession()->getPage();

        while ($deleteBtn = $page->find('css', '.task-table .btn-danger')) {
            $deleteBtn->press();
        }
    }
}
