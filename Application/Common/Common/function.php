<?php
function pr($param){
    echo '<pre>';
    print_r($param);
    echo '</pre>';
}
function prd($param){
    pr($param);
    die;
}