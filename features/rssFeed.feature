Feature:
    In order to make sure RSS reader is working
    As the user
    I have to login to access RSS reader view. Then I check that HTML elements containing feed data are displayed
    If data is not received, HTML elements are not displayed

    Background: Create user
        Given user with username "background-user@test.com" and password "123456" exists

    Scenario: Elements containing feed data should be displayed
        Given I am on "/login"
        Then I fill in "email-input" with "background-user@test.com"
        Then I fill in "password-input" with "123456"
        And I press "Proceed"
        And I am on "/rss-reader"
        And I should see "10 most frequent words in the feed"
        And I should see a ".mdc-data-table__row" element
        And I should see "Feed items"
        And I should see a ".mdc-card" element