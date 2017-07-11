Feature: Testing the task list dashboard
  In order to use the task list
  As an end user
  I need to be able to create, list, and delete tasks

Scenario: Creating a new task
  Given I am on the homepage
  When I provide the task title
  And save the task
  Then I should see this task in the current tasks list
