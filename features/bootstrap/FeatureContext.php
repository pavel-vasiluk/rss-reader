<?php
/**
 * @author Pavel Vasiluk <pavel.vasiluk@gmail.com>
 * Date: 3/14/2020
 * Time: 4:29 PM
 */

namespace Feature;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * FeatureContext class
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * Initializes context
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml
     */
    public function __construct() {}

    /**
     * Wait for element to appear after JS request
     *
     * @Then /^(?:|I )wait for element with id "(?P<id>(?:[^"]|\\")*)" to appear$/
     * @Then /^(?:I )?wait up to (?P<seconds>\d+) second[s]? for element with id "(?P<id>(?:[^"]|\\")*)" to appear$/
     * @Then /^(?:|I )wait for element of class "(?P<class>(?:[^"]|\\")*)" to appear$/
     * @Then /^(?:I )?wait up to (?P<seconds>\d+) second[s]? for element of class "(?P<class>(?:[^"]|\\")*)" to appear$/
     *
     * @param string $id      Element css id
     * @param string $class   Element css class
     * @param int    $seconds Timeout to wait
     *
     * @return NodeElement
     *
     * @throws ExpectationException
     */
    public function waitForElementToAppear(string $id = '', string $class = '', int $seconds = 30)
    {
        $page    = $this->getSession()->getPage();
        $locator = $id ? '#'.$id : '.'.$class;

        $result = $page->waitFor($seconds, function () use ($page, $locator) {
            return $page->find('css', $locator);
        });

        if ($result === null) {
            throw new ExpectationException(
                'Element has not been found on the page!',
                $this->getSession()->getDriver()
            );
        }

        return $result;
    }
}