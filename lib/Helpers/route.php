<?php


function is_pattern_parameter($param)
{
    return substr_count($param, '{') == 1 && substr_count($param, '}') == 1;
}