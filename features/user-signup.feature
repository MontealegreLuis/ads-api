Feature: User sign up
  In order to start selling cool things
  As a guest user
  I want to be able to sign up

  Scenario: Creating an account successfully
    Given I choose a username that has not been taken
    When I sign up
    Then I should be notified that my account was created

  Scenario: Username is already taken
    Given I choose a username that someone has already taken
    When I sign up
    Then I should be asked to choose a different username
