<?php
//@group paracept_3
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check request size form');
$I->openHomePage();
$I->amOnPage(OUT_OF_STOCK_SIZE);
$I->requestSize();