<?php
//@group paracept_2

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check subscribe');
$I->openHomePage();
$I->subscribe();