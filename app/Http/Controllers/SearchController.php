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
        $movie = '《星際異攻隊3》';
        $postFirst = Post::create(['title' => '亞當術士']);
        $postSecond = Post::create(['title' => $movie]);
        foreach (range(1, 10) as $i) {
            $postFirst->comments()->create(['body' => $i . '一旦任務失敗，整個異攻隊也可能被終結']);
            $postSecond->comments()->create(['body' => $i . '堅強地各自奔向自己最後的命運']);
        }
        $results = Search::add(Post::with('comments')->withCount('comments'), 'title')->search($movie);
        echo $movie . '相關註解共' . ' ' . $results->first()->comments_count . ' ' . '筆' . PHP_EOL;
        
        $videoFirst = Video::create(['title' => $movie, 'published_at' => now()->addDay()]);
        $videoSecond = Video::create(['title' => $movie]);
        $results = Search::add(Post::whereNotNull('published_at'), 'title')
            ->add(Video::whereNotNull('published_at'), 'title')
            ->search($movie);
        echo $movie . '相關影片共' . ' ' . count($results) . ' ' . '筆' . PHP_EOL;
    }
}
