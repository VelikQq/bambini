<?php
namespace Pages;

use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;

class Listing
{
    // include url of current page
    public static $URL = '';

    //constants
    const PRODUCT_CARD = '//div[@class="category-list-products"]/div[contains(@class,"product-card")]';
    const PROMOTION = '//div[@class="product-card-label"]/parent::*/parent::*/parent::*';
    const PRODUCT_IMAGE = '//div[@class="product-card-thumbnail"]';
    const PRODUCT_TITLE = '//div[@class="product-card-title"]';
    const PRODUCT_BRAND = '//div[@class="product-card-brand"]';
    const PRODUCT_PRICE = '//span[contains(@class,"product-price-regular")]';
    const CATEGORIES_FILTER = '//ul[contains(@class,"category-product-type-filter-category")]//li';
    const ACC_CATEGORY_FILTER = '//div[contains(@class,"category-accumulative-filter-section--desktop ")]';
    const FILTER_SECTION = '//div[contains(@class,"filter-section")]';
    const TOTAL_ITEMS = '.category-list-title';


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
     * Go to product.
     *
     * @param int $itemNum Sequential number of the item in the listing section
     * @param string $itemXpath
     * @return array itemData
     * @throws Exception
     */
    public function goToProduct($itemXpath, $itemNum): array
    {
        $I = $this->tester;

        if (is_null($itemNum)) {
            $itemNum = $this->getRandomItemNumber($itemXpath);
        }

        $I->lookForwardTo('go to product ' . $itemNum);
        $itemData = $this->getItemData($itemNum, '('.$itemXpath.')');
        $I->moveMouseOver('('.$itemXpath .')[' . $itemNum . ']');
        $I->waitAndClick('('.$itemXpath .')[' . $itemNum . ']', 'go to product ' . $itemNum, true);
        $I->wait(SHORT_WAIT_TIME);
        $I->waitForVisible('.product-single-header', 'product name');
        $actual = $I->grabTextFrom('//h1');
        $I->assertTrue($I->compareStrings($actual, $itemData['title']), 'wrong item card');

        return $itemData;
    }

    /**
     * Check product badge.
     *
     * @param string $itemXpath
     * @throws Exception
     */
    public function checkProductBadge($itemXpath)
    {
        $I = $this->tester;

        $itemNum = $this->getRandomItemNumber($itemXpath);
        $I->lookForwardTo('go to product ' . $itemNum);
        $I->waitForVisible('('.$itemXpath .')[' . $itemNum . ']', 'product card');
        $I->waitForElementClickable('('.$itemXpath .')[' . $itemNum . ']', 30);
    }

    /**
     * Choosing a random accumulative filter from a block of categories.
     *
     * @throws Exception
     */
    public function selectAccumulativeCategoryFilter()
    {
        $I = $this->tester;

        $I->waitForVisible('div.category-accumulative-filter', 'accumulative filter');
        $categoriesName = $I->grabMultiple('span.category-accumulative-filter-section--desktop-toggle-text-inner');
        codecept_debug($categoriesName);
        $categoriesCount = $I->getNumberOfElements(self::ACC_CATEGORY_FILTER);
        $rndCategory = mt_rand(1, $categoriesCount);
        $I->waitAndClick(self::ACC_CATEGORY_FILTER.'[' . $rndCategory . ']', 'select category');
        $I->waitForVisible(self::ACC_CATEGORY_FILTER.'[' . $rndCategory . ']//button[@aria-expanded="true"]', 'selected filter');

        $subCategoriesPath = '//li[contains(@class,"category-accumulative-filter-section--desktop-list-item")]';
        $subCategoryPath = self::ACC_CATEGORY_FILTER.'[' . $rndCategory . ']'.$subCategoriesPath;

        $I->waitForVisible($subCategoryPath, 'subcategories');
        $subCategoriesCount = $I->getNumberOfElements($subCategoryPath);
        $rndSubCategory = mt_rand(1, $subCategoriesCount);
        $I->waitAndClick($subCategoryPath.'[' . $rndSubCategory . ']', 'select subcategory');
        $I->waitForVisible($subCategoryPath.'[' . $rndSubCategory . ']' . '//div[@aria-checked="true"]', 'selected subcategory');

        $I->waitAndClick(self::ACC_CATEGORY_FILTER.'[' . $rndCategory . ']//button[contains(@class,"submit")][not(@disabled="disabled")]',
            'submit accumulative filter');
        $this->waitOverlayLoader();
        $I->waitForVisible(self::ACC_CATEGORY_FILTER.'[' . $rndCategory . ']//span[contains(@class, "counter")]', 'filter counter');
    }

    /**
     * Deselecting a filter from a full filter block.
     *
     * @param string $rndFilter filter name
     * @throws Exception
     */
    public function unselectAccumulativeCategoryFilter($rndFilter)
    {
        $I = $this->tester;

        $I->lookForwardTo('unselect filter from full section');
        $I->waitAndClick(self::ACC_CATEGORY_FILTER.'[' . $rndFilter . ']//span[contains(@class, "counter")]', 'filter');
    }

    /**
     * Getting a random item number.
     *
     * @param string $itemXpath
     * @return int $itemNum random item number
     * @throws Exception
     */
    public function getRandomItemNumber($itemXpath): int
    {
        $I = $this->tester;

        $I->waitForVisible($itemXpath, 'items');
        $items = $I->getNumberOfElements($itemXpath, 'products in grid');
        $I->assertNotEquals($items, 0, 'No available products');
        $itemNum = mt_rand(1, $items);
        if ($itemNum > 5) {
            $itemNum = 5;
        }

        return $itemNum;
    }

    /**
     * Get product data.
     *
     * @param string $itemXpath
     * @param int $itemNum item number
     * @return array item data
     * @throws Exception
     */
    public function getItemData($itemNum, $itemXpath): array
    {
        $I = $this->tester;

        if (is_null($itemNum)) {
            $itemNum = $this->getRandomItemNumber($itemXpath);
        }

        $I->waitForVisible($itemXpath, 'items');
        $I->scrollTo($itemXpath.'['.$itemNum.']', 0, -200);
        $itemTitle = $I->grabTextFrom($itemXpath .'[' . $itemNum . ']' . self::PRODUCT_TITLE);
        $itemBrand = $I->grabTextFrom($itemXpath .'[' . $itemNum . ']' . self::PRODUCT_BRAND);
        $itemPrice = $I->grabTextFrom($itemXpath .'[' . $itemNum . ']' . self::PRODUCT_PRICE);

        return array('brand' => $itemBrand, 'title' => $itemTitle, 'price' => $itemPrice);
    }

    /**
     * Waiting for the product list to be updated.
     *
     * @throws Exception
     */
    public function waitOverlayLoader()
    {
        $I = $this->tester;

        $I->waitForVisible('div.nuxt-progress', 'overlay');
        $I->waitForNotVisible('div.nuxt-progress', 'overlay');
    }

    /**
     * Check pagination.
     *
     * @throws Exception
     */
    public function checkPagination()
    {
        $I = $this->tester;

        $currentXpath = '//li[contains(@class, "pagination-item--current")]';

        $pagination = $I->getNumberOfElements('.pagination-items');
        if (!empty($pagination)) {
            $I->lookForwardTo('check that the first page is selected');
            $I->waitForVisible($currentXpath.'[.=1]', '1 page in pagination');
            $arrayFirstPage = $I->grabMultiple(self::PRODUCT_CARD);
            $I->lookForwardTo('check that the second page is selected');
            $I->waitAndClick('//li[contains(@class, "pagination-item--desktop")][2]', "second page");
            $I->wait(SHORT_WAIT_TIME);
            //$I->waitOverlayLoader();
            $I->waitForVisible($currentXpath.'[.=2]', '2 page in pagination');
            $arraySecondPage = $I->grabMultiple(self::PRODUCT_CARD);
            $I->assertNotEquals($arrayFirstPage, $arraySecondPage, 'The product list has not changed');
        }

        $dotsCount = $I->getNumberOfElements('.pagination-item--truncation');
        if (!empty($dotsCount)) {
            $I->waitForVisible('//li[contains(@class, "pagination-item--truncation")]/following-sibling::li[contains(@class, "pagination-item--desktop")]', 'pagination truncation');
        }

        $forward = $I->getNumberOfElements('.pagination-item--next');
        if (!empty($forward)) {
            $currentPage = $I->grabTextFrom($currentXpath);
            $I->waitAndClick('.pagination-item--next', "next page");
            $I->waitOverlayLoader();
            $forwardPage = $I->grabTextFrom($currentXpath);
            $I->assertGreaterThan($currentPage, $forwardPage, 'moving forward doesnt work');
            $I->waitAndClick('.pagination-item--prev', "previous page");
            $I->wait(SHORT_WAIT_TIME);
            //$I->waitOverlayLoader();
            $currentPage = $I->grabTextFrom($currentXpath);
            $I->assertGreaterThan($currentPage, $forwardPage, 'moving backward doesnt work');
        }
    }

    /**
     * Sorting.
     *
     * @param string $filterName
     * @throws Exception
     */
    public function sortBy($filterName)
    {
        $I = $this->tester;

        $sizeFilters = ['Age', 'Shoe Size'];
        $staticFilters = ['Age', 'Shoe Size', 'Gender', 'Color'];

        $totalItemsCount = $I->getNumberFromLink(self::TOTAL_ITEMS, 'total items count');

        $I->waitForVisible(self::FILTER_SECTION, 'category filter');
        $open = $I->getNumberOfElements(self::FILTER_SECTION . '[contains(.,"' . $filterName . '")]//div[contains(@class, "is-expanded")]');
        if (empty($open)) {
            $this->expandFilter($filterName);
            $I->waitForVisible(self::FILTER_SECTION . '[contains(.,"' . $filterName . '")]//div[contains(@class, "is-expanded")]', 'expanded filter');
        }

        $filters = array_diff($I->grabMultiple(self::FILTER_SECTION . '[contains(.,"' . $filterName . '")]//div[contains(@class,"base-list-item-link--opened")]/../..//div[@class="base-checkbox"]'), array(''));
        $filter = array_rand(array_flip($filters));
        $I->waitAndClick(self::FILTER_SECTION . '//div[@class="base-checkbox"][contains(.,"' . $filter . '")]', 'select filter');
        $I->waitOverlayLoader();
        if (in_array($filterName, $staticFilters)) {
            $sortFilters =  array_diff($I->grabMultiple(self::FILTER_SECTION . '[contains(.,"' . $filterName . '")]//div[contains(@class,"base-list-item-link--opened")]/../..//div[@class="base-checkbox"]'), array(''));
            $I->assertEquals(array_values($filters), array_values($sortFilters), 'number of filters in section changed');
        }

        $sortTotalItemsCount = $I->getNumberFromLink(self::TOTAL_ITEMS, 'sort total items count');
        $I->assertGreaterThan($sortTotalItemsCount, $totalItemsCount, 'wrong total items count after sorting');
        $productsCount = $I->getNumberOfElements(self::PRODUCT_CARD);
        if (in_array($filterName, $sizeFilters)) {
            for ($i = 1; $i <= $productsCount; $i++) {
                $filters = array_diff(preg_split('/\D/', $filter), array('', NULL, false));
                codecept_debug($filters);
                $productPath = self::PRODUCT_CARD . '[' . $i . ']';
                $I->moveMouseOver($productPath);
                $I->wait(SHORT_WAIT_TIME);
                $I->waitForVisible($productPath . '//ul[contains(@class,"product-card-size-tooltip-list")] | //div[contains(@class,"product-card-size-tooltip")]//div[last()]', 'item sizes list');
                $sizes = implode(',', array_diff($I->grabMultiple($productPath . '//ul[contains(@class,"product-card-size-tooltip-list")]//li | //div[contains(@class,"product-card-size-tooltip")]//div[last()]'), array('')));
                foreach ($filters as $f) {
                    preg_match('/'.$f.'[\s\-]|'.$f.'/', $sizes, $matches);
                    if (!empty($matches)) {
                        break;
                    }
                }

                if (empty($matches)) {
                    $I->assertEquals($sizes, 'All sizes upon request', 'no matched sizes');
                }

                $I->moveMouseOver($productPath . '//div[@class="product-card-basic"]');
            }
        }
    }

    /**
     * Sorting designers.
     *
     * @param bool $search
     * @param string $type Top\All brands
     * @throws Exception
     */
    public function sortByDesigners($search, $type)
    {
        $I = $this->tester;

        $totalItemsCount = $I->getNumberFromLink(self::TOTAL_ITEMS, 'total items count');
        $this->expandFilter('Designers');
        $I->waitForVisible('//h3[.="Designers"]', 'Designers filter');
        if ($type == 'All Brands') {
            $topCount = $I->getNumberOfElements('.designers-filter-letter');
            $I->waitAndClick('//div[.="All Brands"]', 'All brands tab');
            $I->waitForVisible('//div[contains(@class,"base-tab--active")][.="All Brands"]', 'selected tab');
            $allCount = $I->getNumberOfElements('.designers-filter-letter');
            $I->assertGreaterThan($topCount, $allCount, 'sorting by tab not working');
        }

        $I->waitForVisible('//div[@class="base-list-item-inner"]', 'designers');
        $designers = $I->grabMultiple('//div[@class="base-list-item-inner"]');
        codecept_debug($designers);
        $designer = array_rand(array_flip($designers));
        if ($search) {
            $I->waitAndFill('//input[@type="text"]', 'designers search input', $designer);
            $result = $I->grabTextFrom('//div[contains(@class,"checkbox-filter-section-checkbox")]');
            $I->assertEquals($designer, $result, 'wrong search result');
        }

        do {
            $I->scrollTo('//div[@class="base-list-item-inner"][contains(.,"' . $designer . '")]', 0, -200);
        } while ($I->waitForElementClickable('//div[@class="base-list-item-inner"][contains(.,"' . $designer . '")]'));

        $I->moveMouseOver('//div[@class="base-list-item-inner"][contains(.,"' . $designer . '")]');
        $I->waitAndClick('//div[@class="base-list-item-inner"][contains(.,"' . $designer . '")]', 'chosen designer', true);
        $I->waitOverlayLoader();
        $I->wait(SHORT_WAIT_TIME);
        $sortTotalItemsCount = $I->getNumberFromLink(self::TOTAL_ITEMS, 'sort total items count');
        $I->assertGreaterThan($sortTotalItemsCount, $totalItemsCount, 'wrong total items count after sorting');
    }

    /**
     * Expand filter section.
     *
     * @param string $filterName
     * @throws Exception
     */
    public function expandFilter($filterName)
    {
        $I = $this->tester;

        $I->waitAndClick(self::FILTER_SECTION . '//span[.="' . $filterName . '"]/../div[contains(@class, "base-list-item-link")]', 'expand filter');
    }

    /**
     * Use multi filter.
     *
     * @throws Exception
     */
    public function multiFilter()
    {
        $I = $this->tester;

        $sectionNum = 1;

        do {
            $totalItemsCount = $I->getNumberFromLink(self::TOTAL_ITEMS, 'total items count');
            $sectionPath = self::FILTER_SECTION .'[not(contains(.,"Designers"))][' . $sectionNum . ']';
            $I->wait(SHORT_WAIT_TIME);
            $open = $I->getNumberOfElements($sectionPath . '//div[contains(@class, "is-expanded")]');
            if (empty($open)) {
                $I->waitAndClick($sectionPath . '//div[contains(@class, "base-list-item-link")]', 'expand filter');
                $I->waitForVisible($sectionPath . '//div[contains(@class, "is-expanded")]', 'expanded filter');
            }

            $filters = $I->grabMultiple($sectionPath . '//div[contains(@class,"base-checkbox")]');
            if (empty($filters)) {
                $categories = $I->grabMultiple($sectionPath . '//div[@class="base-list-item"]');
                $category = array_rand(array_flip($categories));
                $I->waitAndClick(self::FILTER_SECTION . '//div[@class="base-list-item"][contains(.,"' . $category . '")]', 'category');
                $I->waitForVisible('//h3[contains(.,"'.$category.'")]', 'selected category');
                $I->waitForVisible('//div[contains(@class,"base-list-item ")]', 'subcategories');

                $subCategories = $I->getNumberOfElements('//div[contains(@class,"base-list-item")]//div[contains(@class, "base-list-item-link")]');
                if (!empty($subCategories)) {
                    $subCategoryNum = mt_rand(1, $subCategories);
                    $subCategory = $I->grabTextFrom('(//div[contains(@class,"base-list-item")]//div[contains(@class, "base-list-item-link")])['.$subCategoryNum.']/..');
                    $I->waitAndClick('(//div[contains(@class,"base-list-item")]//div[contains(@class, "base-list-item-link")])['.$subCategoryNum.']', 'subcategory');
                    $I->waitForVisible('//h3[contains(.,"'.$subCategory.'")]', 'selected subcategory');

                    $lowCategories = $I->grabMultiple('//div[contains(@class,"base-list-item ")]');
                    if (!empty($lowCategories)) {
                        $lowCategory = array_rand(array_flip($lowCategories));
                        $I->waitAndClick('//div[contains(@class,"base-list-item ")][contains(.,"'.$lowCategory.'")]', 'low category');
                        $I->waitAndClick('//i[contains(@class,"category-filter-header-back")]', 'back button');
                    }
                    $I->waitAndClick('//i[contains(@class,"category-filter-header-back")]', 'back button');
                }
            } else {
                $filter = array_rand(array_flip($filters));
                $I->waitAndClick($sectionPath . '//div[@class="base-checkbox"][contains(.,"' . $filter . '")]', 'select filter');
                $I->waitOverlayLoader();
            }

            $I->wait(MIDDLE_WAIT_TIME);
            $sortTotalItemsCount = $I->getNumberFromLink(self::TOTAL_ITEMS, 'sort total items count');
            $I->assertGreaterThanOrEqual($sortTotalItemsCount, $totalItemsCount, 'wrong total items count after sorting');
            $I->waitAndClick($sectionPath . '//div[contains(@class, "base-list-item-link--opened")]', 'expand filter');
            $I->waitForNotVisible($sectionPath . '//div[contains(@class, "is-expanded")]', 'expanded filter');
            $sectionsCount = $I->getNumberOfElements(self::FILTER_SECTION.'[not(contains(.,"Designers"))]');
            $sectionNum++;

        } while ($sectionNum <= $sectionsCount);
    }

    /**
     * Clear filter.
     *
     * @throws Exception
     */
    public function clearFilter()
    {
        $I = $this->tester;

        $I->scrollTo('.category-header');
        $I->waitAndClick('//a[contains(.,"Clear")]', 'clear filter');
        $I->waitForVisible('//a[contains(.,"Clear")][contains(@class, "is-exact-active")]', 'clear button');
    }

    /**
     * Clear filters.
     *
     * @throws Exception
     */
    public function clearFilters()
    {
        $I = $this->tester;

        $I->waitAndClick('//a[contains(.,"Clear")]', 'clear filter');
        $I->waitForVisible('//a[contains(.,"Clear")][contains(@class,"is-exact-active")]', 'clear button inactive');
    }

    /**
     * Check season sale page.
     *
     * @throws Exception
     */
    public function checkSeasonSale()
    {
        $I = $this->tester;

        $I->waitForVisible(['class' => 'footer-top'], 'footer');
        $I->scrollTo(['class' => 'footer-top']);
        $productsCount = $I->getNumberOfElements(self::PRODUCT_CARD);
        $sortProductsCount = $I->getNumberOfElements('//div[contains(@class,"product-card-badge")]');
        $I->assertEquals($productsCount, $sortProductsCount, 'not all items are on sale');
    }

    /**
     * Check swiping images by arrow.
     *
     * @throws Exception
     */
    public function checkSwipingImagesByArrow()
    {
        $I = $this->tester;

        $itemNum = $this->getRandomItemNumber(self::PRODUCT_CARD);
        $itemXpath = '('.self::PRODUCT_CARD.')[' . $itemNum . ']';
        $imagesCount = $I->getNumberOfElements($itemXpath.'//img');
        $i = 1;
        for (; $i < $imagesCount; ) {
            $I->moveMouseOver($itemXpath.'//button[@title="Next image"]');
            $I->waitAndClick($itemXpath.'//button[@title="Next image"]', "swipe forward", true);
            $I->unFocus();
            $I->scrollTo($itemXpath.'//button[@title="Next image"]', null, -200);
            $I->wait(0.5);
            $i++;
        }

        for (; $i > 1; $i--) {
            $I->moveMouseOver($itemXpath.'//button[@title="Previous image"]');
            $I->waitAndClick($itemXpath.'//button[@title="Previous image"]', "swipe back", true);
            $I->unFocus();
            $I->scrollTo($itemXpath.'//button[@title="Previous image"]', null, -200);
            $I->wait(0.5);
        }
    }

    /**
     * Add to wishlist.
     *
     * @return array item data
     * @throws Exception
     */
    public function addToWishList(): array
    {
        $I = $this->tester;

        $itemData = $I->getItemData(3);
        $I->waitAndClick(self::PRODUCT_CARD.'[3]//*[@title="Add product to Wishlist"]', "add to wishlist");
        $I->waitForVisible('//button[@title="Remove product from Wishlist"] | //h1[.="Login"]', '"Remove product from Wishlist" button or Login title');
        if (!empty($I->getNumberOfElements('//h1[.="Login"]'))) {
            $I->guestCheckout();
            $I->waitForVisible('//button[@title="Remove product from Wishlist"]', 'Remove product from Wishlist button');
        }

        return $itemData;
    }

    /**
     * Quick filters.
     *
     * @throws Exception
     */
    public function quickFilters()
    {
        $I = $this->tester;

        $quickFilterPath = '//a[@class="category-quickfilter-link"]';
        $I->waitForVisible($quickFilterPath, 'quick filters');
        $filtersName = $I->grabMultiple('a.category-quickfilter-link');
        codecept_debug($filtersName);
        $rndFilter = array_rand(array_flip($filtersName));
        $url = $I->grabAttributeFrom($quickFilterPath.'[starts-with(., "'.$rndFilter.'")]', 'href');
        $I->waitAndClick($quickFilterPath.'[starts-with(., "'.$rndFilter.'")]', 'select quick filter');
        try {
            $I->waitForVisible('//span[@class="base-list-item-subtitle"][contains(., "'.$rndFilter.'")]', 'selected quick filter');
        } catch (Exception $e) {
            $I->seeInCurrentUrl(substr($url, strpos($url, "com")+3));
        }
    }
}