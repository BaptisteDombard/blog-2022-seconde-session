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

    public function update(){
        $this->author_model->regenerate_token();

        $view_data = [];
        $view_data['view'] = 'profile/edit-profile.php#api_token';
    }

}