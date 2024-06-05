<?php

use Pages\About as About;
use Pages\Cart as CartPage;
use Pages\Checkout as CheckoutPage;
use Pages\Home as HomePage;
use Pages\Login as LoginPage;
use Pages\Menu as Menu;
use Pages\Search as Search;
use Pages\Listing as ListingPage;
use Pages\Product as ProductPage;
use Pages\Profile as ProfilePage;
use Pages\NotFound as NotFound;
use Pages\Brands as Brands;
use Pages\Registration as Registration;
use Pages\Wishlist as Wishlist;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * Go to the home page of the site.
     *
     * @param string $extraUrl
     * @param array $cookie ['param', 'value']
     * @throws Exception
     */
    public function openHomePage($cookie = null, $extraUrl = '')
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->open($cookie, $extraUrl);
    }

    /**
     * Select category from footer.
     *
     * @throws Exception
     */
    public function selectCategoryFromFooter()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->selectCategoryFromFooter();
    }

    /**
     * Checking required elements on site pages.
     *
     * @throws Exception
     */
    public function checkMandatoryElements()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkMandatoryElements();
    }

    /**
     * Checking footer methods block.
     *
     * @throws Exception
     */
    public function checkFooterMethodsBlock()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkFooterMethodsBlock();
    }

    /**
     * Select category from any section at home page.
     *
     * @param int $sectionNum
     * @throws Exception
     */
    public function selectCategoryHomePage($sectionNum = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->selectCategory($sectionNum, '(//section[@class="landing-section"]/div[@class])');
    }

    /**
     * Select category from any section at not found page.
     *
     * @param int $sectionNum
     * @throws Exception
     */
    public function selectCategoryNotFoundPage($sectionNum = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->selectCategory($sectionNum, '//section[@class="error-section"]');
    }

    /**
     * Go to cart.
     *
     * @throws Exception
     */
    public function goToCart()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToUserMenu('cart');
        $I->waitForElementVisible('h1.cart-title');
    }

    /**
     * Go to wishlist.
     *
     * @throws Exception
     */
    public function goToWishList()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToUserMenu('wishlist');
        $I->waitForElementVisible('//h1[contains(.,"Wishlist")] | //h1[.="Login"]');
        if (!empty($I->getNumberOfElements('//h1[.="Login"]'))) {
            $I->guestCheckout();
        }
        $I->waitForElementVisible('h1.wishlist-title');
    }

    /**
     * Subscribe news letter.
     *
     * @param string $email
     * @throws Exception
     */
    public function subscribe($email = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->subscribe($email);
    }

    /**
     * Check visibility of contacts links.
     *
     * @throws Exception
     */
    public function checkContacts()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkContacts();
    }

    /**
     * Go to profile.
     *
     * @throws Exception
     */
    public function goToProfile()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToUserMenu('account');
        $I->waitForElementVisible('h1.account-title');
    }

    /**
     * Change country.
     *
     * @param string $country
     * @throws Exception
     */
    public function changeCountry($country = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->changeCountry($country);
    }

    /**
     * Get current country.
     *
     * @param bool $iso
     * @return string country
     * @throws Exception
     */
    public function getCurrentCountry($iso = false): string
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getCurrentCountry($iso);
    }

    /**
     * Get current currency.
     *
     * @return string currency Iso-code
     * @throws Exception
     */
    public function getCurrentCurrency(): string
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getCurrentCurrency();
    }

    /**
     * Set Austria.
     *
     * @throws Exception
     */
    public function setDefaultCountry()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->setDefaultCountry();
    }

    /**
     * Change currency.
     *
     * @param string $currency Iso-code
     * @return string symbol
     * @throws Exception
     */
    public function changeCurrency($currency = null): string
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->changeCurrency($currency);
    }

    /**
     * Change language.
     *
     * @throws Exception
     */
    public function changeLanguage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->changeLanguage();
    }

    /**
     * Go to link from information in footer.
     *
     * @param string $linkName
     * @throws Exception
     */
    public function goToLinkFromFooter($linkName)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToLinkFromInformation($linkName, '//nav[@class="footer-expand-inner"]');
    }

    /**
     * Go to link from about page menu.
     *
     * @param string $linkName
     * @throws Exception
     */
    public function goToLinkFromAboutPageMenu($linkName)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToLinkFromInformation($linkName, '//ul[@class="aside-nav-list"]');
    }

    /**
     * Unsubscribe any subscription.
     *
     * @param string $email
     * @param string $subscriptionType wishlist, abandoned_cart
     * @throws Exception
     */
    public function unsubscribe($email, $subscriptionType)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->unsubscribe($email, $subscriptionType);
    }

    /**
     * Check 'change shipping' button in pop up.
     *
     * @throws Exception
     */
    public function changeShippingByAlert()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->changeShippingByAlert();
    }

    /**
     * Get restriction zone data.
     *
     * @param string $restriction tags\manufacturers\products
     * @return array restriction zone data
     * @throws Exception
     */
    public function getRestrictionZoneData($restriction): array
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getRestrictionZoneData($restriction);
    }

    /**
     * Filling in the search string.
     *
     * @param string $text
     * @param bool $submit pressing Enter
     * @throws Exception
     */
    public function searchString($text, $submit = true)
    {
        $I = $this;

        $searchPage = new Search($I);
        $searchPage->searchString($text, $submit);
    }

    /**
     * Go to result from quick search.
     *
     * @param string $text
     * @throws Exception
     */
    public function goToResultFromFastSearch($text = null)
    {
        $I = $this;

        $searchPage = new Search($I);
        $searchPage->goToResultFromFastSearch($text);
    }

    /**
     * Go to result from live search.
     *
     * @param string $section brands\categories\products
     * @throws Exception
     */
    public function goToResultFromLiveSearch($section)
    {
        $I = $this;

        $searchPage = new Search($I);
        $searchPage->goToResultFromLiveSearch($section);
    }

    /**
     * Go to all result from live search.
     *
     * @param string $section brands\products
     * @throws Exception
     */
    public function goToAllResultFromLiveSearch($section)
    {
        $I = $this;

        $searchPage = new Search($I);
        $searchPage->goToAllResultFromLiveSearch($section);
    }

    /**
     * Login.
     *
     * @param string $username
     * @param string $password
     * @throws Exception
     */
    public function doLogin($username = '', $password = '')
    {
        $I = $this;

        if (empty($username)) {
            $username = $this->chooseUser();
            $password = PASSWORD;
        }

        $loginPage = new LoginPage($I);
        $loginPage->doLogin($username, $password);
        $I->wait(SHORT_WAIT_TIME);
        $I->waitForNotVisible('form.sign-in-form', 'sign in form');
    }

    /**
     * Open login page.
     *
     * @throws Exception
     */
    public function openLoginPage()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->openLoginPage();
    }

    /**
     * Returns an array with username and password for authorization.
     *
     * @return array ['username' => '', 'password' => '']
     */
    public function getUser()
    {
        return [
            'username' => $this->grabFromConfig('username'),
            'password' => $this->grabFromConfig('password'),
        ];
    }

    /**
     * Get free user data.
     *
     * @return mixed $cookie page cookie
     * @throws \Exception
     */
    public function chooseUser()
    {
        $userName = '';
        $json = file_get_contents('tests/users.json');
        $jsonArray = json_decode($json, true);
        foreach ($jsonArray as &$userData) {
            if ($userData['isBusy'] == false) {
                $userName = $userData['username'];
                $userData['isBusy'] = $this->getSessionID();
                file_put_contents('tests/users.json', json_encode($jsonArray));

                break;
            }
        }

        return $userName;
    }

    /**
     * Open forgot password form.
     *
     * @throws Exception
     */
    public function forgotPassword()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->forgotPassword();
    }


    /**
     * Logout user.
     *
     * @throws Exception
     */
    public function doLogout()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->doLogout();
    }

    /**
     * Checking an erroneous login / password entry.
     *
     * @throws Exception
     */
    public function doFailLogin()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->doFailLogin();
    }

    /**
     * Open registration form.
     *
     * @throws Exception
     */
    public function openRegistrationForm()
    {
        $I = $this;

        $registrationPage = new Registration($I);
        $registrationPage->openRegistrationForm();
    }

    /**
     * Filling in contact information.
     *
     * @param string $email
     * @param string $name
     * @param string $lastname
     * @param string $password
     * @return string email
     * @throws Exception
     */
    public function fillRegistrationContactForm($email = null, $name = BUYER_NAME, $lastname = BUYER_LAST_NAME, $password = PASSWORD): string
    {
        $I = $this;

        $registrationPage = new Registration($I);
        return $registrationPage->fillRegistrationContactForm($email, $name, $lastname, $password, false);
    }

    /**
     * Filling in contact information on not available email.
     *
     * @param string $email
     * @param string $name
     * @param string $lastname
     * @param string $password
     * @throws Exception
     */
    public function failRegistration($email = null, $name = BUYER_NAME, $lastname = BUYER_LAST_NAME, $password = PASSWORD)
    {
        $I = $this;

        $registrationPage = new Registration($I);
        $registrationPage->fillRegistrationContactForm($email, $name, $lastname, $password, true);
    }

    /**
     * Switch to sign-in.
     *
     * @throws Exception
     */
    public function switchToSignIn()
    {
        $I = $this;

        $registrationPage = new Registration($I);
        $registrationPage->openRegistrationForm();
    }

    /**
     * Go to product from listing.
     *
     * @param int $itemNum
     * @param string $extraPath
     * @return array item data
     * @throws Exception
     */
    public function goToProductFromListing($itemNum = null, $extraPath = null): array
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProduct($listingPage::PRODUCT_CARD.$extraPath, $itemNum);
    }

    /**
     * Check product badge.
     *
     * @throws Exception
     */
    public function checkProductBadgeFromListing()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkProductBadge($listingPage::PRODUCT_CARD.'//div[@class="product-card-badge"]');
    }

    /**
     * Go to product from recently viewed.
     *
     * @param int $itemNum
     * @return array item data
     * @throws Exception
     */
    public function goToProductFromRecentlyViewed($itemNum = null): array
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProduct('//div[contains(@class,"recent")]'.ProductPage::SLIDE_PRODUCT_CARD, $itemNum);
    }

    /**
     * Go to product from cross sale.
     *
     * @param int $itemNum
     * @return array item data
     * @throws Exception
     */
    public function goToProductFromCrossSale($itemNum = null): array
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProduct(ProductPage::CROSS_SALE.ProductPage::SLIDE_PRODUCT_CARD, $itemNum);
    }

    /**
     * Choosing a random accumulative filter from a block of categories.
     *
     * @throws Exception
     */
    public function selectAccumulativeCategoryFilter()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->selectAccumulativeCategoryFilter();
    }

    /**
     * Get item data from listing.
     *
     * @param int $itemNum item number
     * @param string $itemPath item path
     * @return array item data
     * @throws Exception
     */
    public function getItemData($itemNum = null, $itemPath = \Pages\Listing::PRODUCT_CARD): array
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getItemData($itemNum, $itemPath);
    }

    /**
     * Getting a random item number.
     *
     * @param string $itemXpath
     * @return int random item number
     * @throws Exception
     */
    public function getRandomItemNumber($itemXpath): int
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getRandomItemNumber($itemXpath);
    }

    /**
     * Check pagination.
     *
     * @throws Exception
     */
    public function checkPagination()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkPagination();
    }

    /**
     * Sorting.
     *
     * @param string $filterName
     * @throws Exception
     */
    public function sortBy($filterName)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->sortBy($filterName);
    }

    /**
     * Sorting designers.
     *
     * @param bool $search
     * @param string $type Top\All brands
     * @throws Exception
     */
    public function sortByDesigners($search = false, $type = 'Top Brands')
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->sortByDesigners($search, $type);
    }

    /**
     * Clear filter section.
     *
     * @throws Exception
     */
    public function clearFilter()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->clearFilter();
    }

    /**
     * Clear filters.
     *
     * @throws Exception
     */
    public function clearFilters()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->clearFilters();
    }

    /**
     * Use multi filter.
     *
     * @throws Exception
     */
    public function multiFilter()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->multiFilter();
    }

    /**
     * Expand filter section.
     *
     * @param string $filterName
     * @throws Exception
     */
    public function expandFilter($filterName)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->expandFilter($filterName);
    }

    /**
     * Check season sale page.
     *
     * @throws Exception
     */
    public function checkSeasonSale()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSeasonSale();
    }

    /**
     * Check swiping images by arrow.
     *
     * @throws Exception
     */
    public function checkSwipingImagesByArrowAtListing()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSwipingImagesByArrow();
    }

    /**
     * Add to wishlist.
     *
     * @return array item data
     * @throws Exception
     */
    public function addToWishListFromCatalog(): array
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->addToWishList();
    }

    /**
     * Quick filters.
     *
     * @throws Exception
     */
    public function quickFilters()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->quickFilters();
    }

    /**
     * Go to category from top menu.
     *
     * @param string $category name of category
     * @throws Exception
     */
    public function goToMenuCategory($category)
    {
        $I = $this;

        $menuPage = new Menu($I);
        $menuPage->goToMenuCategory($category);
    }

    /**
     * Go to category by "viewAll".
     *
     * @param string $category name of category
     * @throws Exception
     */
    public function goToMenuCategoryByViewAll($category)
    {
        $I = $this;

        $menuPage = new Menu($I);
        $menuPage->viewAll($category);
    }

    /**
     * Go to category by banner.
     *
     * @param string $category name of category
     * @throws Exception
     */
    public function goToMenuCategoryByBanner($category)
    {
        $I = $this;

        $menuPage = new Menu($I);
        $menuPage->goToMenuCategoryByBanner($category);
    }

    /**
     * Go to subcategory.
     *
     * @param string $category
     * @return string subcategory
     * @throws Exception
     */
    public function goToSubcategory($category): string
    {
        $I = $this;

        $menuPage = new Menu($I);
        return $menuPage->goToSubcategory($category);
    }

    /**
     * See proline.
     *
     * @throws Exception
     */
    public function seeProline()
    {
        $I = $this;

        $menuPage = new Menu($I);
        $menuPage->seeProline();
    }

    /**
     * Check transition to category.
     *
     * @param string $category name of category
     * @throws Exception
     */
    public function categoryCheck($category)
    {
        $I = $this;

        $menuPage = new Menu($I);
        $menuPage->categoryCheck($category);
    }

    /**
     * Waiting for the product list to be updated.
     *
     * @throws Exception
     */
    public function waitOverlayLoader()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->waitOverlayLoader();
    }

    /**
     * Adding a product to the cart from product page.
     *
     * @param bool $close close pop-up cart
     * @return array item data
     * @throws Exception
     */
    public function addToCartFromProductPage($close = false): array
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addToCart($close);
    }

    /**
     * Repetitive adding to cart.
     *
     * @return bool repeat
     * @throws Exception
     */
    public function repetitiveAdding(): bool
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->repetitiveAdding();
    }

    /**
     * Adding a product to the cart from "shop the outfit" product page.
     *
     * @param bool $close close pop-up cart
     * @param int $itemNum
     * @return array item data
     * @throws Exception
     */
    public function addToCartFromShopOutfit($close = false, $itemNum = null): array
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addSmallCardToCart($productPage::OUTFIT_PRODUCT, $close, $itemNum);
    }

    /**
     * Select size.
     *
     * @param string $extraXpath
     * @param bool $repeat repetitive adding
     * @return string size
     * @throws Exception
     */
    public function selectSize($extraXpath = '', $repeat = false): string
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->selectSize($extraXpath, $repeat);
    }

    /**
     * Getting id from the product page.
     *
     * @param string $extraXpath
     * @return mixed item id
     * @throws Exception
     */
    public function getItemId($extraXpath = '')
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->getItemId($extraXpath);
    }

    /**
     * Add to wishlist.
     *
     * @return string name
     * @throws Exception
     */
    public function addToWishList(): string
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addToWishList();
    }

    /**
     * Request size.
     *
     * @param string $extraXpath
     * @param string $email
     * @param bool $resend
     * @throws Exception
     */
    public function requestSize($extraXpath = '', $email = null, $resend = false)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->requestSize($extraXpath, $email, $resend);
    }

    /**
     * Check size guide.
     *
     * @throws Exception
     */
    public function checkSizeGuide()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkSizeGuide();
    }

    /**
     * Change color.
     *
     * @throws Exception
     */
    public function changeColor()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->changeColor();
    }

    /**
     * Switch Tab.
     *
     * @param string $tabName
     * @throws Exception
     */
    public function switchTab($tabName)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->switchTab($tabName);
    }

    /**
     * Check swiping images by arrow.
     *
     * @throws Exception
     */
    public function checkSwipingImagesByArrow()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkSwipingImagesByArrow(false, '//div[contains(@class, "product-single-carousel")]//button');
    }

    /**
     * Check swiping big images by arrow.
     *
     * @throws Exception
     */
    public function checkSwipingBigImagesByArrow()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkSwipingImagesByArrow(true, '//div[@class="product-overlay"]//button');
    }

    /**
     * Select big image in overlay.
     *
     * @throws Exception
     */
    public function selectBigImage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectImage(true);
    }

    /**
     * Select image in overlay.
     *
     * @throws Exception
     */
    public function selectImage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectImage(false);
    }

    /**
     * Check swiping products in carousel.
     *
     * @param string $extraPath
     * @throws Exception
     */
    public function checkSwipingProductsCarousel($extraPath = '')
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkSwipingProductsCarousel($extraPath);
    }

    /**
     * Go to product from carousel.
     *
     * @param string $extraPath
     * @throws Exception
     */
    public function goToProductFromCarousel($extraPath = '')
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->goToProductFromCarousel($extraPath);
    }

    /**
     * Move on carousel.
     *
     * @throws Exception
     */
    public function moveOnCarousel()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->moveOnCarousel();
    }

    /**
     * Remove from wishlist at product page.
     *
     * @throws Exception
     */
    public function removeFromWishListAtProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->removeFromWishList();
    }

    /**
     * Get applied voucher at product page.
     *
     * @return string voucher
     * @throws Exception
     */
    public function getAppliedVoucherAtProductPage(): string
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->getAppliedVoucher();
    }

    /**
     * Filling in the voucher field.
     *
     * @param string $voucher
     * @param string $message error message
     * @param bool $active active or inactive
     * @param int $fixedVoucher
     * @return array order data
     * @throws Exception
     */
    public function useVoucher($voucher, $message = '', $active = true, $fixedVoucher = 0): array
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->useVoucher($voucher, $message, $active, $fixedVoucher);
    }

    /**
     * Dismiss voucher.
     *
     * @throws Exception
     */
    public function dismissVoucher()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->dismissVoucher();
    }

    /**
     * Calculation of the total amount.
     *
     * @param int $delivery
     * @param int $fixedVoucher
     * @param int $duties
     * @param array $previousDiscount
     * @return array order data
     * @throws Exception
     */
    public function totalAmountCalculation($delivery = 0, $fixedVoucher = 0, $previousDiscount = array(), $duties = 0): array
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->totalAmountCalculation($delivery, $fixedVoucher, $previousDiscount, $duties);
    }

    /**
     * Calculation of the sub-total amount.
     *
     * @throws Exception
     */
    public function subTotalAmountCalculation()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->subTotalAmountCalculation();
    }

    /**
     * Go to checkout.
     *
     * @throws Exception
     */
    public function goToCheckout()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToCheckout();
    }

    /**
     * Go to product from cart.
     *
     * @throws Exception
     */
    public function goToProductFromCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToProduct();
    }

    /**
     * Check delivery & return at cart.
     *
     * @throws Exception
     */
    public function checkDeliveryAndReturn()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDeliveryAndReturn();
    }

    /**
     * Check delivery calculator.
     *
     * @throws Exception
     */
    public function checkDeliveryCalculator()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDeliveryCalculator();
    }

    /**
     * Continue shopping from cart.
     *
     * @throws Exception
     */
    public function continueShoppingFromTopUserPages()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->continueShopping();
    }

    /**
     * Check countdown appear.
     *
     * @throws Exception
     */
    public function checkCountdownAppear()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkCountdownAppear();
    }

    /**
     * Check countdown disappear.
     *
     * @throws Exception
     */
    public function checkCountdownDisappear()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkCountdownDisappear();
    }

    /**
     * Change item quantity.
     *
     * @throws Exception
     */
    public function changeItemQuantity()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->changeItemQuantity();
    }

    /**
     * Delete item.
     *
     * @throws Exception
     */
    public function deleteItem()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->deleteItem();
    }

    /**
     * Clean cart.
     *
     * @throws Exception
     */
    public function cleanCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->cleanCart();
    }

    /**
     * Filling in personal data at checkout.
     *
     * @param string $address
     * @param string $country
     * @param string $city
     * @param string $postcode
     * @param bool $submit go to shipping
     * @throws Exception
     */
    public function fillContacts($country = BUYER_COUNTRY, $address = BUYER_ADDRESS, $city = BUYER_CITY, $postcode = BUYER_POSTCODE, $submit = true)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->fillContacts($country, $address, $city, $postcode, $submit);
    }

    /**
     * Fill in address.
     *
     * @param string $address2
     * @throws Exception
     */
    public function fillAddress($address2 = '')
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->fillAddress(BUYER_COUNTRY, BUYER_ADDRESS, $address2, BUYER_CITY, BUYER_POSTCODE);
    }

    /**
     * Choose shipping method.
     *
     * @param string $type
     * @param int $duties
     * @return int price
     * @throws Exception
     */
    public function chooseShippingMethod($type = 'Standard'): int
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        return $checkoutPage->chooseShippingMethod($type);
    }

    /**
     * Sign-in instead from reset password page.
     *
     * @throws Exception
     */
    public function signInInstead()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->signInInstead();
    }

    /**
     * Enter separate billing address.
     *
     * @throws Exception
     */
    public function separateBillingAddress()
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->fillContacts(BUYER_COUNTRY, BUYER_ADDRESS, BUYER_CITY, BUYER_POSTCODE, false);
        $checkoutPage->separateBillingAddress();
    }

    /**
     * Edit or add shipping/billing address.
     *
     * @param string $type
     * @param string $func
     * @throws Exception
     */
    public function editOrAddAddress($type, $func)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->editOrAddAddress($type, $func);
    }

    /**
     * Change preferred shipping/billing address.
     *
     * @param string $type
     * @throws Exception
     */
    public function changePreferredAddress($type)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->changePreferredAddress($type);
    }

    /**
     * Guest checkout.
     *
     * @return string email
     * @throws Exception
     */
    public function guestCheckout(): string
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        return $checkoutPage->guestCheckout();
    }

    /**
     * Filling in card data.
     *
     * @param string $cardNumber
     * @throws Exception
     */
    public function fillCardData($cardNumber = BUYER_STRIPES['nonAuth'])
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->fillCardData($cardNumber);
    }

    /**
     * Sing up in link pay.
     *
     * @param string $cardNumber
     * @throws Exception
     */
    public function linkPaySignUp($cardNumber = BUYER_STRIPES['nonAuth'])
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->linkPaySignUp($cardNumber);
    }

    /**
     * Save to link pay.
     *
     * @param string $cardNumber
     * @throws Exception
     */
    public function saveToLinkPay($cardNumber = BUYER_STRIPES['nonAuth'])
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->saveToLinkPay($cardNumber);
    }

    /**
     * Save credit card.
     *
     * @throws Exception
     */
    public function saveCreditCard()
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->saveCreditCard();
    }

    /**
     * Check saved credit card.
     *
     * @throws Exception
     */
    public function checkSavedCard()
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->checkSavedCard();
    }

    /**
     * Delete saved credit card.
     *
     * @throws Exception
     */
    public function deleteSavedCard()
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->deleteSavedCard();
    }

    /**
     * Change shipping from checkout.
     *
     * @throws Exception
     */
    public function changeShippingButton()
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->changeShipping();
    }

    /**
     * Add payment method.
     *
     * @throws Exception
     */
    public function addPaymentMethod()
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->addPaymentMethod();
    }

    /**
     * Complete 3D Secure.
     *
     * @param bool $authorize
     * @throws Exception
     */
    public function complete3DSecure($authorize = true)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->complete3DSecure($authorize);
    }

    /**
     * Choose payment type.
     *
     * @param string $paymentType
     * @throws Exception
     */
    public function choosePayment($paymentType)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->choosePayment($paymentType);
    }

    /**
     * Dismiss alert.
     *
     * @param string $message
     * @throws Exception
     */
    public function dismissAlert($message)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->dismissAlert($message);
    }

    /**
     * Filling in card data.
     *
     * @return array sofort data
     * @throws Exception
     */
    public function fillSofort(): array
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        return $checkoutPage->fillSofort();
    }

    /**
     * Filling in PayPal.
     *
     * @return string amount
     * @throws Exception
     */
    public function fillPayPal(): string
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        return $checkoutPage->fillPayPal();
    }

    /**
     * Confirm order.
     *
     * @return string total sum
     * @throws Exception
     */
    public function confirmOrder(): string
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        return $checkoutPage->confirmOrder();
    }

    /**
     * See thank-you page.
     *
     * @throws Exception
     */
    public function seeThankYouPage()
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->seeThankYouPage();
    }

    /**
     * View order at thank you page.
     *
     * @param string $orderNum
     * @throws Exception
     */
    public function viewOrder($orderNum)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->viewOrder($orderNum);
    }

    /**
     * Getting the order number from the "thank you for order" page.
     *
     * @return string order number
     * @throws Exception
     */
    public function getOrderNumber(): string
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        return $checkoutPage->getOrderNumber();
    }

    /**
     * Change Import & Duty Services.
     *
     * @param bool $active true = included
     * @return int duties
     * @throws Exception
     */
    public function changeDDP($active = true): int
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        return $checkoutPage->changeDDP($active);
    }

    /**
     * Choose shipping method.
     *
     * @param string $country Iso-code
     * @param string $currency Iso-code
     * @throws Exception
     */
    public function checkDeliveryLimits($country, $currency)
    {
        $I = $this;

        $checkoutPage = new CheckoutPage($I);
        $checkoutPage->checkDeliveryLimits($country, $currency);
    }

    /**
     * Random selection from the alphabet panel,
     * then randomly select a brand from the alphabetical section.
     *
     * @throws Exception
     */
    public function selectBrand()
    {
        $I = $this;

        $brandsPage = new Brands($I);
        $brandsPage->selectBrand();
    }

    /**
     * Removing an item from wishlist.
     *
     * @param int $itemNum
     * @throws Exception
     */
    public function deleteProductFromWishList($itemNum = null)
    {
        $I = $this;

        $wishlistPage = new Wishlist($I);
        $wishlistPage->deleteProduct($itemNum);
    }

    /**
     * Clean wishlist.
     *
     * @throws Exception
     */
    public function cleanWishlist()
    {
        $I = $this;

        $I->waitForVisible(Wishlist::WISHLIST_TOP, 'wishlist in header');
        $itemsCount = $I->getNumberFromLink(Wishlist::WISHLIST_TOP, 'item count in wishlist');
        if (!empty($itemsCount)) {
            $I->goToWishList();
            $itemsCount = $I->getNumberOfElements(Wishlist::PRODUCT_CARD, 'products in grid');
            for ($i = 1; $i <= $itemsCount; $i++) {
                $I->deleteProductFromWishList();
            }
        }
    }

    /**
     * Add to cart.
     *
     * @param bool $close close pop-up cart
     * @param int $itemNum
     * @return array item data
     * @throws Exception
     */
    public function addToCartFromWishlist($close = true, $itemNum = null): array
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addSmallCardToCart(Wishlist::PRODUCT_CARD, $close, $itemNum);
    }

    /**
     * Go to product from wishlist.
     *
     * @param int $itemNum
     * @throws Exception
     */
    public function goToProductFromWishList($itemNum = null)
    {
        $I = $this;

        $wishlistPage = new Wishlist($I);
        $wishlistPage->goToProduct($itemNum);
    }

    /**
     * Add new delivery address at profile.
     *
     * @throws Exception
     */
    public function addDeliveryAddressAtProfile()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->addDeliveryAddress();
    }

    /**
     * View my orders.
     *
     * @throws Exception
     */
    public function viewMyOrders()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->viewMyOrders();
    }

    /**
     * Select order.
     *
     * @throws Exception
     */
    public function selectOrder()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->selectOrder();
    }

    /**
     * Go to product card from order.
     *
     * @throws Exception
     */
    public function goToProductFromOrder()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToProductFromOrder();
    }

    /**
     * Remove last shipping address.
     *
     * @throws Exception
     */
    public function removeDeliveryAddress()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->removeDeliveryAddress();
    }

    /**
     * Change user data.
     *
     * @param string $name
     * @param string $lastName
     * @param string $email
     * @param string $newPass
     * @throws Exception
     */
    public function changeUserData($name, $lastName, $email, $newPass)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->changeUserData($name, $lastName, $email, $newPass);
    }

    /**
     * Go to a tab from the navigation bar.
     *
     * @param string $tabName
     * @throws Exception
     */
    public function goToTabFromNavigationPanel($tabName)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToTabFromNavigationPanel($tabName);
    }

    /**
     * Continue shopping for pop-up.
     *
     * @throws Exception
     */
    public function continueShopping()
    {
        $I = $this;

        //$I->waitForElementVisible('div.overlay--product-added-to-cart');
        $I->waitAndClick('//div[contains(@class,"overlay--product-added-to-cart")]//span[.="Continue shopping"]', 'continue shopping', true);
        //$I->waitForElementNotVisible('div.overlay--product-added-to-cart');
    }

    /**
     * Go to cart from pop up.
     *
     * @throws Exception
     */
    public function goToCartFromPopUp()
    {
        $I = $this;

        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick('//button[contains(.,"Shopping bag")]', 'go to cart', true);
        $I->subTotalAmountCalculation();
    }

    /**
     * Do saved card.
     *
     * @param bool $auth
     * @throws Exception
     */
    public function doSavedCard($auth = true)
    {
        $I = $this;

        $repeat = 0;
        do {
            $I->goToMenuCategory(\Pages\Menu::SALE);
            $I->goToProductFromListing();
            $I->addToCartFromProductPage();
            $I->goToCheckout();
            if (!$auth && empty($repeat)) {
                $I->guestCheckout();
            }

            $I->fillContacts();
            $I->chooseShippingMethod();
            if (empty($repeat)) {
                $I->fillCardData();
                $I->saveCreditCard();
                $I->confirmOrder();
                $I->seeThankYouPage();
            }

            $repeat++;
        } while ($repeat <= 1);
    }

    /**
     * Getting the current address.
     */
    public function getCurrentUrlJS()
    {
        return $this->executeJS("return location.href");
    }

    /**
     * Click on an element with Js.
     *
     * @param string $path path to element
     */
    public function clickJs($path)
    {
        $I = $this;

        $I->executeJS($path.'.click();');
    }

    /**
     * Clear cookies.
     *
     * @throws Exception
     */
    public function clearCookies()
    {
        $I = $this;

        $I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
            $webdriver->manage()->deleteAllCookies();
        });
    }

    /**
     * Clear local storage.
     *
     * @throws Exception
     */
    public function clearLocalStorage()
    {
        $I = $this;

        $I->executeJS('window.localStorage.clear();');
    }

    /**
     * Wait page.
     *
     * @throws Exception
     */
    public function waitPage()
    {
        $I = $this;

        $I->executeJS('function pollDOM() {
            if (document.readyState !== "complete") {
                setTimeout(pollDOM, 1000);
            }
        }
        
        pollDOM();');
    }

    /**
     * Implicitly waiting for an element using Js.
     *
     * @param string $path
     * @throws Exception
     */
    public function waitUntilJs($path)
    {
        $I = $this;

        $I->executeJS('function pollDOM () {
            if ('.$path.' == null) {
                setTimeout(pollDOM, 1000);
            }
        }
        
        pollDOM();');
    }

    /**
     * Fill input field with Js.
     *
     * @param string $path
     * @param string $text
     * @throws Exception
     */
    public function fillFieldJS($path, $text)
    {
        $I = $this;

        $I->executeJS($path.'.value = "'.$text.'"');
    }
}
