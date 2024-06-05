<?php

// This is global bootstrap for autoloading
/** time to wait for the element to appear on the page */
const ELEMENT_WAIT_TIME = 30;

/** long waiting time */
const LONG_WAIT_TIME = 10;

/** short waiting time */
const SHORT_WAIT_TIME = 2;

/** middle waiting time */
const MIDDLE_WAIT_TIME = 4;

/** vouchers */
const INVALID_VOUCHER = 'welcom10';
const CASH_PROMO_VOUCHER = '';
const CASHBACK_VOUCHER = '';

/** buyer's name used in tests */
const BUYER_NAME = "Tester";

/** buyer's last name used in tests */
const BUYER_LAST_NAME = "Autotester";

/** buyer's email used in tests */
const BUYER_EMAIL = "";

/** buyer's name used in tests */
const PASSWORD = "";

/** buyer's phone used in tests */
const BUYER_PHONE = "+";

/** buyer's address used in tests */
const BUYER_ADDRESS = "Museumsplatz 1";

/** country name used in tests */
const BUYER_COUNTRY = "Austria";

/** city name used in tests */
const BUYER_CITY = "Wien";

/** city's postcode used in tests */
const BUYER_POSTCODE = "1070";

/** buyer's stripes used in tests */
const BUYER_STRIPES = [
    'nonAuth' => '',
    'auth' => '',
    'pauper' => ''
];

/** buyer's paypal login used in tests */
const PAYPAL_LOGIN = '';

/** buyer's paypal password used in tests */
const PAYPAL_PASS = '';

/** buyer's sofort credentials */
const SOFORT_CODE = [
    'Germany' => '',
    'Belgium' => '',
    'other' => ''
];

/** User for saved card test */
const USER_SAVED_CARD = "";

/** User for change data test */
const USER_CHANGE = "";

/** new email for change data test */
const NEW_EMAIL = "";

/** new password for change data test */
const NEW_PASSWORD = "";

const URLS_NON_UNICODE = [
    'dёsigners',
    'moschÛno',
    'baby/boсс',
    'boy/gucci/päge=2',
    'search/baby klädung',
    'moschino/teddy-gift-träcksuit-with-hooded-jacket-in-white-34979.html',
    'wishlisт',
    'shopping-bög',
    'инфо/delivery',
    'checkаут/shipping',
    'accaunt',
    'baby/8-bit,balmain,gucci/18-mth,2-yrs,3-mth/brown,red,silver,stripes,white/page=2'
];



const OUT_OF_STOCK_SIZE = 'the-new-society/pink-flowers-all-over-cotton-summer-hat-in-off-white-174333.html';

const QUERY_PARAMS_URLS = [
    'boy',
    'search/boy',
    'molo/happy-smile-organic-sweatshirt-in-black-182390.html',
    'shopping-bag',
];