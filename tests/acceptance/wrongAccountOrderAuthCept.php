<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check redirect from wrong account order');
$I->openHomePage();
$I->doLogin();
$I->amOnPage('account/orders/bla');
$I->see('Welcome');