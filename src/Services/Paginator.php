<?php
namespace App\Services;

Class Paginator
{
    public function getPartial($data, $offset ,$length)
    {

        return array_slice($data, $offset, $length);
    }
}