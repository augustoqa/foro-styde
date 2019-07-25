<?php

use App\Comment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentListTest extends FeatureTestCase
{
    function test_a_user_can_see_the_post_comments()
    {
        $comment = factory(Comment::class)->create([
            'comment' => 'Este es el comentario'
        ]);

        $this->visit($comment->post->url)
            ->see('Este es el comentario');
    }

    function test_the_comments_are_paginated()
    {
        $post = $this->createPost();

        $oldComment = factory(Comment::class)->create([
            'comment' => 'Comentario mas antiguo',
            'post_id' => $post->id,
            'created_at' => Carbon::now()->subDay(2),
        ]);

        factory(Comment::class)->times(15)->create([
            'post_id' => $post->id,
            'created_at' => Carbon::now()->subDay()
        ]);

        $newComment = factory(Comment::class)->create([
            'comment' => 'Comentario mas reciente',
            'post_id' => $post->id,
            'created_at' => Carbon::now()
        ]);

        $this->visit($post->url)
            ->see($newComment->comment)
            ->dontSee($oldComment->comment)
            ->click('2')
            ->see($oldComment->comment)
            ->dontSee($newComment->comment);
    }

    function test_show_the_author_in_comment()
    {
        $author = $this->defaultUser([
            'first_name' => 'Cesar',
            'last_name' => 'Acual'
        ]);

        $post = $this->createPost([
            'user_id' => $author->id
        ]);

        $comment = factory(Comment::class)->create([
            'post_id' => $post->id,
            'user_id' => $author->id
        ]);

        $this->visit($comment->post->url)
            ->see($author->name);
    }
}
