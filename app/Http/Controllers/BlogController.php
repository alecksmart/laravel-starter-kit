<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use \App\Post;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Debug if needed
        /*if('local' === \App::environment()){
            \DB::listen(function ($sql) {
                echo '<pre>';
                    var_dump($sql->sql);
                echo '</pre>';
            });
        }*/
        // The default view retrieves and shows paginated posts
        $posts = Post::where('is_approved', 1)
            ->orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(Config::get('constants.PAGINATE_RECORDS_PER_PAGE'));

        return view('blog', ['posts' => $posts]);
    }
}
