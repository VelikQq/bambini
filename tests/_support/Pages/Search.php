<?php
namespace Pages;

use Exception;
use Facebook\WebDriver\WebDriverKeys;

class Search
{
    // include url of current page
    public static $URL = '/search/';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    /**
     * Filling in the search string.
     *
     * @param string $text
     * @param bool $submit pressing Enter
     * @throws Exception
     */
    public function searchString($text, $submit)
    {
        $I = $this->tester;

        $I->waitAndClick('//li[contains(@class,"search")]', 'open search');
        $I->waitAndFill('#search-input', 'search field', $text);
        if ($submit) {
            $I->pressKey('#search-input', WebDriverKeys::ENTER);
            $I->waitOverlayLoader();
            $searchResult = $I->grabTextFrom('//h1');
            $I->assertStringContainsString(strtoupper($text), $searchResult, 'wrong request result');
        }
    }

    /**
     * Go to result from quick search.
     *
     * @throws Exception
     */
    public function goToResultFromFastSearch()
    {
        $I = $this->tester;

        $resultsCount = $I->getNumberOfElements('//div[@class="search-list-item"]', 'products in fast search');
        $rndNum = mt_rand(1, $resultsCount);
        $text = $I->grabTextFrom('//div[@class="search-list-item"][' . $rndNum . ']');
        $I->waitAndClick('//div[@class="search-list-item"][' . $rndNum . ']', 'select result by num');
        $I->waitOverlayLoader();
        $searchResult = $I->grabTextFrom('//h1');
        $I->assertStringContainsString(strtoupper($text), $searchResult, 'wrong request result');
    }

    /**
     * Go to result from live search.
     *
     * @param string $section brands\categories\products\recent
     * @throws Exception
     */
    public function goToResultFromLiveSearch($section)
    {
        $I = $this->tester;

        $sectionPath = '//div[contains(@class, "livesearch-list-category--'.$section.'")]//a[not(contains(.,"View All"))]';
        $resultsCount = $I->getNumberOfElements($sectionPath, $section.' in live search');
        $rndNum = mt_rand(1, $resultsCount);
        switch ($section) {
            case 'products':
                $sectionName = $I->grabTextFrom('('.$sectionPath.')[' . $rndNum . ']//div[@class="livesearch-list-item--product-title"]');
                $I->waitForVisible('//div[contains(@class, "livesearch-list-category--categories")]', 'category of product');
                $I->waitForVisible('//div[contains(@class, "livesearch-list-category--brands")]', 'designer of product');
                break;
            case 'brands':
                $sectionName = $I->grabTextFrom('('.$sectionPath.')[' . $rndNum . ']');
                $I->waitForVisible('//div[contains(@class, "livesearch-list-category--products")]', 'products');
                break;
            default:
                $sectionName = $I->grabTextFrom('('.$sectionPath.')[' . $rndNum . ']');
        }

        $I->waitAndClick('('.$sectionPath.')[' . $rndNum . ']', $sectionName);
        $I->waitOverlayLoader();
        $searchResult = $I->grabTextFrom('//h1');
        if (!empty(strstr($sectionName, '> '))) {
            $sectionName = preg_replace('/> /', '', strstr($sectionName, '> '));
        }

        $I->assertStringContainsString(strtoupper($sectionName), strtoupper($searchResult), 'wrong request result');
    }

    /**
     * Go to all result from live search.
     *
     * @param string $section brands\products
     * @throws Exception
     */
    public function goToAllResultFromLiveSearch($section)
    {
        $I = $this->tester;

        $I->waitAndClick('//span[contains(., "View All '.$section.'")]', 'all '.$section.' button');
        $I->waitOverlayLoader();
    }
}