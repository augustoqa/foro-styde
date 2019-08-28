<?php

use App\Category;
use App\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListPostIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    function test_the_posts_can_filter_by_category()
    {
        $java = factory(Category::class)->create([
            'name' => 'Java'
        ]);

        $php = factory(Category::class)->create([
            'name' => 'PHP'
        ]);

        $postJava = factory(Post::class)->create([
            'title' => 'Post de Java',
            'category_id' => $java->id,
        ]);

        $postPHP = factory(Post::class)->create([
            'title' => 'Post de PHP',
            'category_id' => $php->id,
        ]);

        $posts = Post::orderBy('created_at', 'ASC')->category($java)->get();

        $this->assertTrue($postJava->title === $posts->first()->title);

        $this->assertFalse($postPHP->title === $posts->last()->title);
    }
}
