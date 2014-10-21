Feature: Login
  In order to login
  As a not logged user
  I need to be able to login

  Scenario: Login when non existing user
    Given I am on "/login"
    When I fill in "email" with "non.existing.email@gmail.com"
    And I fill in "password" with "foo"
    And I press "login"
    Then I should see "Log in"
