<?php
//@group paracept_4
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check resend request size form');
$I->openHomePage();
$I->amOnPage(OUT_OF_STOCK_SIZE);
$str = $I->generateString(5);
$email = $str.'@bambinifashion.com';
$I->requestSize('', $email);
$I->requestSize('', $email, true);