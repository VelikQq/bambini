<?php
//@group paracept_1
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check using Gender and Color filters separately');
$I->openHomePage();
$I->goToMenuCategory(\Pages\Menu::NEW_IN);
$sections = ['Gender', 'Color'];
foreach ($sections as $section) {
    $I->sortBy($section);
    $I->clearFilter();
}