<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check registration on not available email');
$I->openHomePage();
$I->openRegistrationForm();
$I->failRegistration(BUYER_EMAIL);