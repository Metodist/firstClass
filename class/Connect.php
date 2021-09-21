<?php

class connect
{
    public static function make(){
        return new PDO("mysql:host=127.0.0.1; dbname=phptraning;", "root", "root");
    }
}