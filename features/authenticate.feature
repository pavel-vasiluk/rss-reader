Feature:
    In order to get access to the rss reader
    As the user
    I need to be able to sign up and login.

    Background: Create user
        Given user with username "background-user@test.com" and password "123456" exists

    Scenario: User must be able to sign up with valid credentials
        Given I am on "/sign-up"
        Then I should not have user with email "user1@test.com"
        Then I fill in "email-input" with "user1@test.com"
        Then I fill in "password-input" with "123456"
        Then I press "sign-up-btn"
        Then I wait for element of class "success-response-msg" to appear
        And I should see "User with email user1@test.com has been successfully verified. Now you can proceed to"
        And I follow "Login"
        And I am on "/login"

    Scenario Outline: User must not be able to sign up with invalid data
        Given I am on "/sign-up"
        Then I should not have user with email "user1@test.com"
        Then I fill in "email-input" with "<emailFieldValue>"
        Then I fill in "password-input" with "<passwordFieldValue>"
        And I press "Sign Up"
        Then I wait for element of class "error-response-msg" to appear
        And I should see "<expectedError>"
    Examples:
        | emailFieldValue             | passwordFieldValue | expectedError                  |
        | abc                         | 123456             | Not a valid email.             |
        | background-user@test.com    | 123456             | This email is already used     |
        | user1@test.com              |                    | This value should not be blank |
        |                             | 123456             | This value should not be blank |

    Scenario Outline: Email field validation on the fly is working well
        Given I am on "/sign-up"
        Then I fill in "email-input" with "<fieldValue>"
        Then I wait for element of class "error-response-msg" to appear
        And I should see "<expectedResult>"
    Examples:
        | fieldValue                  | expectedResult             |
        | abc                         | Not a valid email.         |
        | background-user@test.com    | This email is already used |

    Scenario: User must be able to login with valid credentials
        Given I am on "/login"
        Then I fill in "email-input" with "background-user@test.com"
        Then I fill in "password-input" with "123456"
        And I press "Proceed"
        Then I am on "/rss-reader"

    Scenario Outline: User must not be able to login with invalid credentials
        Given I am on "/login"
        Then I should not have user with email "user1@test.com"
        Then I fill in "email-input" with "<emailFieldValue>"
        Then I fill in "password-input" with "<passwordFieldValue>"
        And I press "Proceed"
        And I should see "<expectedResult>"

    Examples:
        | emailFieldValue           | passwordFieldValue | expectedResult            |
        | user1@test.com            | 123456             | Email could not be found. |
        | background-user@test.com  | 654321             | Invalid credentials.      |

    Scenario: Anonymous user cannot access RSS reader view without login
        Given I am on "/rss-reader"
        # As the redirect has to happen
        And I should see "Login"