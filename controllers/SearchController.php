<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\DB;
use app\core\Request;
use app\core\Response;
use app\models\Post;
use MongoDB\BSON\ObjectId;

class SearchController extends Controller
{
    public function index(Request $request) {
        $this->setLayout('main');
        return $this->render('search', [
            'post' => new Post(),
            'posts' => DB::get()->posts->find()
        ]);
    }

    public function showPosts(Request $request) {
        $imgMatch = $request->getRequestData()['imgTitle'];
        $query = [
            'imgTitle' => [
                '$regex' => $imgMatch,
                '$options' => 'i'
            ]
        ];
        $posts = DB::get()->posts->find($query);
        $ready = '';
        foreach ($posts as $post) {
            $ready .= sprintf(
                "<div class='card mb-4' style='width: 18rem'>
                     <a href='uploads/watermark/%s'>
                        <img src='uploads/mini/%s' class='card-img-top'>
                     </a>
                     <div class='card-body'>
                        <h5 class='card-title'>%s</h5>
                        <p class='card-text'>%s</p>
                        <p class='card-text'>%s</p>
                     </div>
                 </div>",
                $post['imgFile'],
                $post['imgFile'],
                $post['imgTitle'],
                $post['author'],
                date('d/m/Y H:i', $post['when'])
            );
        }
        return $ready;
    }
}