<?php


function is_post(){
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function is_get(){
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function is_delete(){
    return $_SERVER['REQUEST_METHOD'] === 'DELETE';
}

function is_put(){
    return $_SERVER['REQUEST_METHOD'] === 'PUT';
}

