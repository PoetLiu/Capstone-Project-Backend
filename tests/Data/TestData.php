<?php

namespace Tests\Data;

abstract class TestData
{
    const URI_USER = "/api/user/";
    const EMAIL = "test@gmail.com";
    const UNKNOWN_EMAIL = "notexist@gmail.com";
    const INVALID_EMAIL = "@gmail.com";
    const USER_NAME = "test";
    const PWD = "12345678";
    const INVALID_PWD = "87654321";
}