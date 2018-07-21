Feature: User sign up
  In order to start selling cool things
  As a guest user
  I want to be able to sign up

  Scenario: Username is already taken
    Given a poster with the username "thomas_anderson"
    When I sign up with the username "thomas_anderson"
    Then I should be asked to choose a different username

  Scenario: Creating an account successfully
    Given a poster with the username "thomas_anderson"
    When I sign up with the username "elliot_alderson"
    Then I should be notified that my account was created
