Feature: Login
  In order to login
  As a not logged user
  I need to be able to login

  Scenario:
    Given I am not signed in
    When I run "ls"
    Then I should get: false
