<?php
namespace Pages;

use Exception;

class Product
{
    // include url of current page
    public static $URL = '';

    //constants
    const SIZES_LIST = '//ul[@class="product-size-select-list"]//li';
    const SLIDE_PRODUCT_CARD = '//div[contains(@class,"product-carousel-slide")]';
    const REGULAR_PRICE = '//div[contains(@class,"product-single-price")]';
    const IMAGE_PATH = '//div[contains(@class,"product-thumbnails-thumbnail")]';
    const CROSS_SALE = '//div[contains(@class,"crosssale")]';
    const OUTFIT_PRODUCT = '//div[contains(@class,"outfit-product product-card")]';

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
     * Adding a product to the cart.
     *
     * @param bool $close close pop-up cart
     * @return array item data
     * @throws Exception
     */
    public function addToCart($close): array
    {
        $I = $this->tester;

        $id = '';
        $size = '';
        $I->waitForVisible('div.product-single-form', 'product card');
        $I->waitForNotVisible('//button[@aria-label="Sold out"]', 'product out of stock');
        if (empty($I->getNumberOfElements('//div[@class="product-size-select-onesize"]'))) {
            $id = $I->getItemId();
            $size = $I->selectSize('//div[@class="product-single-form"]');
        }

        $name = $I->grabTextFrom('h1.product-single-description');
        $reduced = $I->getNumberOfElements(self::REGULAR_PRICE.'//span[@class="product-price-reduced"]');
        if (empty($reduced)) {
            $price = $I->getNumberFromLink(self::REGULAR_PRICE, 'price');
        } else {
            $price = $I->getNumberFromLink(self::REGULAR_PRICE.'//span[@class="product-price-reduced"]', 'price');
        }

        $I->waitAndClick('//button[@title="Add to bag"]', "buy button");
        switch ($close) {
            case 'skipp':
                echo "skipped";
                break;
            case true:
                $I->continueShopping();
                break;
            default:
                $I->goToCartFromPopUp();
        }

        return array('id' => $id, 'size' => $size, 'price' => $price, 'name' => $name);
    }

    /**
     * Repetitive adding to cart.
     *
     * @return bool repeat
     * @throws Exception
     */
    public function repetitiveAdding(): bool
    {
        $I = $this->tester;

        $repeat = true;
        $I->waitForVisible('div.product-single-form', 'product card');
        if (empty($I->getNumberOfElements('//div[@class="product-size-select-onesize"]'))) {
            $I->selectSize('', $repeat);
        }

        $I->waitAndClick('//button[@title="Add to bag"]', "buy button");
        $I->waitForVisible(Home::OVERLAY, '__overlay');
        $outOfStock = $I->getNumberOfElements('//div[.="There isn\'t enough products in stock."]');
        if (!empty($outOfStock)) {
            echo PHP_EOL.'out of stock work correctly';
            $repeat = false;
        } else {
            $I->dismissAlert('Item successfully added');
        }

        return $repeat;
    }

    /**
     * Adding a product to the cart from "shop the outfit".
     *
     * @param int $itemXpath
     * @param bool $close close pop-up cart
     * @param int $itemNum
     * @return array item data
     * @throws Exception
     */
    public function addSmallCardToCart($itemXpath, $close, $itemNum): array
    {
        $I = $this->tester;

        $id = '';
        $size = '';
        if (is_null($itemNum)) {
            $itemNum = $I->getRandomItemNumber($itemXpath);
        }

        $itemXpathNum = $itemXpath.'[' . $itemNum . ']';
        $I->waitForVisible($itemXpathNum, 'item');
        $I->waitForNotVisible($itemXpathNum.'//span[@class="product-card-sold"]', 'product out of stock');
        if (!empty($I->getNumberOfElements($itemXpathNum.'//i[contains(@class,"icon-plus")]'))) {
            $I->waitAndClick($itemXpathNum.'//i[contains(@class,"icon-plus")]', 'expand product card');
        }

        $I->waitForNotVisible('//div[@class="product-card-options"][@style="display: none;"]', 'product card options');
        if (empty($I->getNumberOfElements($itemXpathNum.'//div[@class="product-size-select-onesize"]'))) {
            $id = $I->getItemId($itemXpathNum);
            $size = $I->selectSize($itemXpathNum);
            $I->wait(SHORT_WAIT_TIME);
        }

        $itemData = $I->getItemData($itemNum, $itemXpath);
        $I->waitAndClick($itemXpathNum.'//button[contains(@title,"to bag")]', "buy button");
        if ($close) {
            $I->continueShopping();
        } else {
            $I->goToCartFromPopUp();
        }

        $itemData['size'] = $size;
        $itemData['id'] = $id;

        return $itemData;
    }

    /**
     * Select size.
     *
     * @param string $extraXpath
     * @param bool $repeat repetitive adding
     * @return string $size
     * @throws Exception
     */
    public function selectSize($extraXpath, $repeat): string
    {
        $I = $this->tester;

        $size = '';
        $I->waitForNotVisible(Home::OVERLAY, 'overlay');
        $I->waitAndClick($extraXpath.'//div[contains(@class,"product-size-select")]', "open size list");
        $I->waitForVisible($extraXpath.self::SIZES_LIST, 'product sizes list');
        if ($repeat) {
            $I->waitAndClick('('.$extraXpath.self::SIZES_LIST.'/div[not(contains(@class,"is-sold"))])[1]', "select size");
        } else {
            $sizes = $I->grabMultiple($extraXpath.self::SIZES_LIST.'/div[not(contains(@class,"is-sold"))]');
            $size = array_rand(array_flip($sizes));
            $I->wait(SHORT_WAIT_TIME);
            $I->moveMouseOver($extraXpath.self::SIZES_LIST.'[contains(.,"' . $size . '")]');
            $I->waitAndClick($extraXpath.self::SIZES_LIST.'[contains(.,"' . $size . '")]', "select size", true);
        }

        return $size;
    }

    /**
     * Request size.
     *
     * @param string $extraXpath
     * @param string $email
     * @param bool $resend
     * @throws Exception
     */
    public function requestSize($extraXpath, $email, $resend)
    {
        $I = $this->tester;

        if (is_null($email)){
            $str = $I->generateString(5);
            $email = $str.'@bambinifashion.com';
        }

        $I->waitForVisible($extraXpath.'//div[starts-with(@class,"product-")]', 'product card');
        if (!empty($I->getNumberOfElements($extraXpath.'//i[contains(@class,"icon-plus")]'))) {
            $I->waitAndClick($extraXpath.'//i[contains(@class,"icon-plus")]', 'expand product card');
        }

        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick($extraXpath.'//div[contains(@class,"product-size-select")]', "open size list");
        $I->waitForVisible($extraXpath.self::SIZES_LIST, 'product sizes list');
        $I->waitAndClick($extraXpath.self::SIZES_LIST.'/div[contains(@class,"is-sold")]', "select sold size");
        $I->waitForVisible('//div[@class="overlay-content"]//input[@name="email"]', 'email input');
        $I->waitAndFill('//div[@class="overlay-content"]//input[@name="email"]', 'email', $email);
        $I->waitAndClick('//button[contains(.,"Send")]', "send request");
        if ($resend) {
            $I->dismissAlert('Size request for this product has already been sent. We are on it!');
        } else {
            $I->dismissAlert('Your request has been sent');
        }
    }

    /**
     * Check size guide.
     *
     * @throws Exception
     */
    public function checkSizeGuide()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Size guide")]', "open size guide");
        $I->waitAndClick('//button[contains(.,"Switch to cm")]', "switch to cm");
        $I->waitForVisible('//button[contains(.,"Switch to inches")]', '"Switch to inches" button');
        $I->waitAndClick('//button[contains(.,"Switch to inches")]', "switch to inches");
        $I->waitForVisible('//button[contains(.,"Switch to cm")]', '"Switch to cm" button');
        $I->dismissAlert('Switch to cm');
    }

    /**
     * Change color.
     *
     * @throws Exception
     */
    public function changeColor()
    {
        $I = $this->tester;

        $I->waitForVisible('ul.product-color-triggers', 'product color trigger');
        $names = $I->grabMultiple('//div[@class="product-color-trigger-link"]', 'title');
        $name = array_rand(array_flip($names));
        $I->waitAndClick('//div[@class="product-color-trigger-link"][@title="'.$name.'"]', "switch color");
        $I->waitForVisible('//h1[.="'.$name.'"]', 'product name '.$name);
    }

    /**
     * Switch Tab.
     *
     * @param string $tabName
     * @throws Exception
     */
    public function switchTab($tabName)
    {
        $I = $this->tester;

        $I->waitAndClick('//button[.="'.$tabName.'"]', "switch to ".$tabName);
        $I->waitForVisible('//button[contains(@class,"is-selected")][.="'.$tabName.'"]', 'selected tab '.$tabName);
    }

    /**
     * Check swiping images by arrow.
     *
     * @param bool $fullScreen
     * @param string $extraPath
     * @throws Exception
     */
    public function checkSwipingImagesByArrow($fullScreen, $extraPath)
    {
        $I = $this->tester;

        $I->waitForVisible('//div[contains(@class,"product-image-carousel-container-content")]', 'swiper');
        if ($fullScreen) {
            $I->waitAndClick('//div[contains(@class,"product-image-carousel-container-content")]', 'open big picture', true);
            $I->waitForVisible('div.product-overlay', 'scroll wrap');
        }

        $imagesCount = $I->getNumberOfElements(self::IMAGE_PATH);
        $i = 1;
        for (; $i < $imagesCount; ) {
            $I->waitAndClick($extraPath.'[@title="Next image"]', "swipe forward");
            $I->unFocus();
            $I->scrollTo($extraPath.'[@title="Next image"]', null, -200);
            $I->wait(0.5);
            $I->waitForVisible(self::IMAGE_PATH.'['.($i+1).'][contains(@class,"active")]', 'selected image');
            $i++;
        }

        for (; $i > 1; $i--) {
            $I->waitAndClick($extraPath.'[@title="Previous image"]', "swipe back");
            $I->unFocus();
            $I->scrollTo($extraPath.'[@title="Previous image"]', null, -200);
            $I->wait(0.5);
            $I->waitForVisible(self::IMAGE_PATH.'['.($i-1).'][contains(@class,"active")]', 'selected image');
        }
    }

    /**
     * Select image.
     *
     * @param bool $fullScreen
     * @throws Exception
     */
    public function selectImage($fullScreen)
    {
        $I = $this->tester;

        if ($fullScreen) {
            $I->waitAndClick('//div[contains(@class,"product-image-carousel-container-content")]
', 'open big picture');
            $I->waitForVisible('div.product-overlay', 'scroll wrap');
        }

        $imagesCount = $I->getNumberOfElements(self::IMAGE_PATH);
        $imgNum = mt_rand(2, $imagesCount);
        $I->waitAndClick(self::IMAGE_PATH.'['.$imgNum.']', "select image");
        $I->waitForVisible(self::IMAGE_PATH.'['.$imgNum.'][contains(@class,"active")]', 'selected image');
    }

    /**
     * Check swiping products in carousel.
     *
     * @param string $extraPath
     * @throws Exception
     */
    public function checkSwipingProductsCarousel($extraPath)
    {
        $I = $this->tester;

        if (empty($extraPath)) {
            $this->moveOnCarousel();
        }

        $I->waitAndClick($extraPath.self::CROSS_SALE.'//*[@title="Next image"]', "sсroll forward");
        $I->waitForVisible($extraPath.self::CROSS_SALE.'//*[@title="Previous image"][not(contains(@class, "disabled"))]', 'previous image button');
        $I->waitAndClick($extraPath.self::CROSS_SALE.'//*[@title="Previous image"]', "sсroll back");
        $I->waitForNotVisible($extraPath.self::CROSS_SALE.'//*[@title="Previous image"][not(contains(@class, "disabled"))]', 'previous image button');
    }

    /**
     * Go to product from carousel.
     *
     * @param string $extraPath
     * @throws Exception
     */
    public function goToProductFromCarousel($extraPath)
    {
        $I = $this->tester;

        if (empty($extraPath)) {
            $this->moveOnCarousel();
        }

        $carouselProducts = '//div[contains(@class,"product-carousel-slide")]';
        $itemNum = $I->getRandomItemNumber($extraPath.$carouselProducts);
        $I->lookForwardTo('go to product ' . $itemNum);
        $itemData = $I->getItemData($itemNum, '('.$extraPath.$carouselProducts.')');
        $I->waitAndClick('('.$extraPath.$carouselProducts .')[' . $itemNum . ']', 'go to product ' . $itemNum, true);
        $I->wait(SHORT_WAIT_TIME);
        $I->waitForVisible('.product-single-header', 'product name');
        $actual = $I->grabTextFrom('//h1');
        $I->assertTrue($I->compareStrings($actual, $itemData['title']), 'wrong item card');

        return $itemData;
    }

    /**
     * Move on carousel.
     *
     * @throws Exception
     */
    public function moveOnCarousel()
    {
        $I = $this->tester;

        $I->scrollTo('#product-carousels');
        $I->waitForVisible('//div[contains(@class,"product-carousel-container")]', 'product carousel');
    }

    /**
     * Getting Id from the product page.
     *
     * @param string $extraXpath
     * @return mixed item Id
     * @throws Exception
     */
    public function getItemId($extraXpath)
    {
        $I = $this->tester;

        return preg_replace("/\D/", '', $I->grabAttributeFrom($extraXpath.'//button[contains(@id,"product")]', 'id'));
    }

    /**
     * Add to wishlist.
     *
     * @return string name
     * @throws Exception
     */
    public function addToWishList(): string
    {
        $I = $this->tester;

        $I->waitForVisible('div.product-single-form', 'product card');
        $name = $I->grabTextFrom('h1.product-single-description');
        $I->waitAndClick('.product-add-to-wishlist', "add to wishlist");
        $I->waitForVisible('//button[@title="Remove product from Wishlist"] | //h1[.="Login"]', '"Remove product from Wishlist" button or Login title');
        if (!empty($I->getNumberOfElements('//h1[.="Login"]'))) {
            $I->guestCheckout();
            $I->wait(SHORT_WAIT_TIME);
            $I->waitForVisible('//button[@title="Remove product from Wishlist"]', "Remove product from Wishlist button");
        }

        return $name;
    }

    /**
     * Remove from wishlist.
     *
     * @throws Exception
     */
    public function removeFromWishList()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[@title="Remove product from Wishlist"]', "remove from wishlist");
        $I->waitForVisible('//button[@title="Add product to Wishlist"]', '"Add product to Wishlist" button');
    }

    /**
     * Get applied voucher.
     *
     * @return string voucher
     * @throws Exception
     */
    public function getAppliedVoucher(): string
    {
        $I = $this->tester;

        $I->waitForVisible('//div[contains(@class, "product-single-label")]', 'product promotion label');

        return trim(explode(':', $I->grabTextFrom('(//span[contains(@class, "product-single-label")])[1]'))[1]);
    }
}