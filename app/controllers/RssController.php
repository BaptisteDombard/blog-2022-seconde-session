<?php

namespace Blog\Controllers;

use Blog\Models\Author;
use Blog\Models\Category;
use Blog\Models\Post;
use Blog\ViewComposers\AsideData;

class RssController
{
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
        } else {
            define('POSTS_COUNT', $this->post_model->count());
        }

        // Pagination setting from request
        define('MAX_PAGE', intdiv(POSTS_COUNT, PER_PAGE) + (POSTS_COUNT % PER_PAGE ? 1 : 0));

        $p = START_PAGE;
        if (isset($_GET['p'])) {
            if ((int) $_GET['p'] >= START_PAGE && (int) $_GET['p'] <= MAX_PAGE) {
                $p = (int) $_GET['p'];
            }
        }

        // Main data for request
        $posts = $this->post_model->get($filter, $sort_order, $p);

        // Rendering
        $view_data = [];
        $view_data['view'] = 'posts/rss.php';
        $aside_data = $this->fetch_aside_data();
        $view_data['data'] = array_merge($aside_data, compact('posts', 'p'));

        return $view_data;
    }
}