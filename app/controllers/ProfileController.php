<?php

namespace Blog\Controllers;

use Blog\Models\Author;
use Blog\Models\Category;
use Blog\Models\Post;
use Blog\ViewComposers\AsideData;

class ProfileController
{
    use AsideData;

    public function __construct(
        private readonly Author $author_model = new Author(),
        private readonly Category $category_model = new Category(),
        private readonly Post $post_model = new Post(),
    ){
    }

    public function edit(): array{
        $view_data = [];
        if ($_SESSION['connected_author']){
            $view_data['view'] = 'profile/edit-profile.php';
        }else{
            $view_data['view'] = 'posts/index.php';
        }
        $view_data['data'] = $this->fetch_aside_data();
        return $view_data;
    }
}