<?php
namespace App\Services;

Class Sum
{
    public function getPartial($x, $y)
    {

        return "the result is:" . ($x + $y);
    }
}