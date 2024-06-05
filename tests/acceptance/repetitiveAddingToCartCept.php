<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('add product to cart till it becomes out of stock');
$I->openHomePage();
$I->selectCategoryHomePage();
$I->goToProductFromListing();
do {
    $repeat = $I->repetitiveAdding();
} while ($repeat == true);