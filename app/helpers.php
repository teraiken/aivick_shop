<?php

if (! function_exists('addTax')) {
    function addTax($price) {
        $tax = 10;
        
        return floor($price * ($tax + 100) / 100);
    }
}