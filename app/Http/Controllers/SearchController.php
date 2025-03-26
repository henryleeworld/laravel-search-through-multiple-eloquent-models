<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Video;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class SearchController extends Controller
{
    public function show() 
    {
        $movie = __('“Mobile Suit Gundam: Char\'s Counterattack”');
        $postFirst = Post::create(['title' => __('“Mobile Suit Gundam Hathaway”')]);
        $postSecond = Post::create(['title' => $movie]);
        foreach (range(1, 10) as $i) {
            $postFirst->comments()->create(['body' => $i . __('After Char\'s rebellion, Hathaway Noa leads an insurgency against Earth Federation, but meeting an enemy officer and a mysterious woman alters his fate.')]);
            $postSecond->comments()->create(['body' => $i . __('Thirteen years after the war, the Neo Zeon army threatens the peace. Armed with the Nu Gundam, Amuro Ray and Federation forces take the field once more.')]);
        }
        $results = Search::add(Post::with('comments')->withCount('comments'), 'title')->search($movie);
        echo $movie . __(' :comments_count related comments.', ['comments_count' => $results->first()->comments_count]) . PHP_EOL;
        
        $videoFirst = Video::create(['title' => $movie, 'published_at' => now()->addDay()]);
        $videoSecond = Video::create(['title' => $movie]);
        $results = Search::add(Post::whereNotNull('published_at'), 'title')
            ->add(Video::whereNotNull('published_at'), 'title')
            ->search($movie);
        echo $movie . __(' :movies_count related movies.', ['movies_count' => count($results)]) . PHP_EOL;
    }
}
