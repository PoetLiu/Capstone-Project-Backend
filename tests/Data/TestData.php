<?php

namespace Tests\Data;

abstract class TestData
{
    const URI_USER = "/api/user/";
    const EMAIL = "test@gmail.com";
    const UNKNOWN_EMAIL = "notexist@gmail.com";
    const INVALID_EMAIL = "@gmail.com";
    const NEW_EMAIL = "new_test@gmail.com";
    const PHONE = "2268998888";
    const USER_NAME = "test";
    const NEW_USER_NAME = "new_test";
    const PWD = "12345678";
    const NEW_PWD = "12344321";
    const INVALID_PWD = "87654321";
    const FISTNAME = "Hello";
    const LASTNAME = "World";
    const ADDRESS = "888 Timbercroft Cresent";
    const CITY = "Waterloo";
    const POSTCODE = "N6T 3J2";

    const PRODUCT_BRAND = 'xiaomi';
    const PRODUCT_NAME = 'Smart Band Pro 7';
    const PRODUCT_DESC = 'A awsome smart band ever.';
    const PRODUCT_SPEC = '10cm * 2cm';
    const PRODUCT_PRICE = '177.99';
    const PRODUCT_ONSALE_PRICE = '159.99';
    const PRODUCT_STOCK = '10';
    const PRODUCT_FEATURED = 'false';
    const PRODUCT_IMG = 'smartband.png';

    const REVIEW_TITLE = 'Good Product';
    const REVIEW_CONTENT = 'I like it very much.';
    const REVIEW_STARS = 5;
}