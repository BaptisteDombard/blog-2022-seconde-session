<?php

namespace Blog\Controllers;

use stdClass;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Blog\Models\Post;
use Blog\Models\Author;
use Blog\Models\Category;
use Cocur\Slugify\Slugify;
use JetBrains\PhpStorm\NoReturn;
use Blog\ViewComposers\AsideData;
use Blog\Request\Validators\StorePostRequest;

class APIController
{
    use StorePostRequest;
    use AsideData;

    public function __construct(
        private readonly Author $author_model = new Author(),
        private readonly Category $category_model = new Category(),
        private readonly Post $post_model = new Post(),
    ) {
    }

    public function index(): array
    {
        // Order setting from request
        $sort_order = isset($_GET['order-by']) && $_GET['order-by'] === 'oldest' ? 'ASC' : DEFAULT_SORT_ORDER;

        // Filter setting from request
        $filter = [];
        if (isset($_GET['category'])) {
            $filter['type'] = 'category';
            $filter['value'] = $_GET['category'];
            define('POSTS_COUNT', $this->post_model->count_by_category($_GET['category']));
        } elseif (isset($_GET['author'])) {
            $filter['type'] = 'author';
            $filter['value'] = $_GET['author'];
            define('POSTS_COUNT', $this->post_model->count_by_author($_GET['author']));
        }

        // Main data for request
        $posts = $this->post_model->get($filter, $sort_order);

        // Rendering
        $view_data = [];
        $view_data['view'] = 'api/api.php';
        $aside_data = $this->fetch_aside_data();
        $view_data['data'] = array_merge($aside_data, compact('posts'));

        return $view_data;
    }
}