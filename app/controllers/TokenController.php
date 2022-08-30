<?php

namespace Blog\Controllers;

use Blog\Models\Author;
use Blog\ViewComposers\AsideData;

class TokenController
{
    use AsideData;

    public function __construct(
        private readonly Author $author_model = new Author()
    ){
    }

}