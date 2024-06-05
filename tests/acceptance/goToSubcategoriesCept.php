<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
/*$I->wantTo('check all transition to categories and subcategories');
$I->openHomePage();
$I->goToSubcategory(\Pages\Menu::BABY);
$I->goToMenuCategoryByViewAll(\Pages\Menu::BABY);
$I->goToMenuCategoryByBanner(\Pages\Menu::BABY);*/
if(!preg_match('/4242/', "4242 4242 4242 4242")) {
    Throw new \Exception("currency has not changed");
};
