<?php
function test_helper()
{
    return '给你变个宝贝,李晨晖';
}

function pig()
{
    return '李晨晖';
}

function who()
{
    return '谁是猪';
}

function route_class()
{
    return str_replace('.','-',Route::currentRouteName());
}
