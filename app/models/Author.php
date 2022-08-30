<?php

namespace Blog\Models;

use stdClass;

class Author extends Model
{
    public function get(): array
    {
        $sql = <<<SQL
                SELECT a.id,
                       a.name, 
                       a.avatar, 
                       a.slug, 
                       a.email,
                       a.api_token,
                       count(posts.id) as posts_count
                FROM posts
                JOIN authors a on posts.author_id = a.id
                GROUP BY a.id
            SQL;

        return $this->pdo_connection->query($sql)->fetchAll();
    }

    public function find_by_slug($slug): stdClass|bool
    {
        $sql = <<<SQL
            SELECT * FROM authors WHERE slug = :slug;
        SQL;
        $statement = $this->pdo_connection->prepare($sql);
        $statement->execute([':slug' => $slug]);

        return $statement->fetch();
    }

    public function find_by_email($email): stdClass|bool
    {
        $sql = <<<SQL
            SELECT * FROM authors WHERE email = :email;
        SQL;
        $statement = $this->pdo_connection->prepare($sql);
        $statement->execute([':email' => $email]);

        return $statement->fetch();
    }

    public function regenerate_token(){
        $connected_author_name = $_SESSION['connected_author']->name;
        function str_rand ( int $length = 32)
        {
            $length = ($length < 4) ? 4 : $length;
            return bin2hex(random_bytes(($length - ($length % 2)) /2));
        }
        $new_token = str_rand();

        $sql = <<<SQL
            UPDATE authors SET api_token = $new_token WHERE name = $connected_author_name
        SQL;

        $this->pdo_connection->query($sql)->fetch();
    }
}