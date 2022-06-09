<?php use Carbon\Carbon;?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon"
          sizes="180x180"
          href="/apple-touch-icon.png">
    <link rel="icon"
          type="image/png"
          sizes="32x32"
          href="/favicon-32x32.png">
    <link rel="icon"
          type="image/png"
          sizes="16x16"
          href="/favicon-16x16.png">
    <link rel="manifest"
          href="/site.webmanifest">
    <link rel="mask-icon"
          href="/safari-pinned-tab.svg"
          color="#0ed3cf">
    <meta name="msapplication-TileColor"
          content="#0ed3cf">
    <meta name="theme-color"
          content="#0ed3cf">

    <title>Posts - My Awesome Blog</title>

    <link href="https://tailwindcomponents.com/css/component.blog-page.css"
          rel="stylesheet">
</head>
<body class="bg-gray-200">
    <pre>
        <? xml version="1.0"?>
        <rss version="2.0">
            <channel>
                <title>My awesome blog</title>
                <link>http://blog.test/</link>
                <description>The Awesome blog of Dominique Vilain</description>
                <language>fr-be</language>
                <?php foreach ($data['posts'] as $post): ?>
                    <item>
                    <title><?= $post->post_title ?></title>
                    <link>http://blog.test/?action=show&amp;resource=post&amp;slug=<?= $post->post_slug ?></link>
                    <description><?= $post->post_excerpt ?></description>
                    <pubDate><?= carbon::create($post->post_published_at)->toRfc822String()?></pubDate>
                </item>
                <?php endforeach;?>
            </channel>
        </rss>
    </pre>
</body>
</html>