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
        $movie = '尚氣與十環傳奇';
        $postFirst = Post::create(['title' => '黑寡婦']);
        $postSecond = Post::create(['title' => $movie]);
        foreach (range(1, 10) as $i) {
            $postFirst->comments()->create(['body' => $i . '一家人攜手作戰']);
            $postSecond->comments()->create(['body' => $i . '充滿濃濃的東方元素']);
        }
        $results = Search::add(Post::with('comments')->withCount('comments'), 'title')->get('尚氣與十環傳奇');
        echo $movie . '相關註解共' . ' ' . $results->first()->comments_count . ' ' . '筆' . PHP_EOL;
        
        $videoFirst = Video::create(['title' => $movie, 'published_at' => now()->addDay()]);
        $videoSecond = Video::create(['title' => $movie]);
        $results = Search::add(Post::whereNotNull('published_at'), 'title')
            ->add(Video::whereNotNull('published_at'), 'title')
            ->get($movie);
        echo $movie . '相關影片共' . ' ' . count($results) . ' ' . '筆' . PHP_EOL;
    }
}
