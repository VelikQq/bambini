<?php
//@group paracept_1

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check sign-in instead button');
$I->openHomePage();
$I->openLoginPage();
$I->signInInstead();