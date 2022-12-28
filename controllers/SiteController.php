<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\DB;
use app\core\Request;
use app\core\Response;
use app\models\Post;
use MongoDB\BSON\ObjectId;

class SiteController extends Controller
{
    public function home()
    {
        $page = Application::$app->session->get('page') ? Application::$app->session->get('page') : 1;
        $pageSize = 4;
        $opts = [
            'sort' => ['when' => -1],
            'skip' => ($page - 1) * $pageSize,
            'limit' => $pageSize,
        ];
        $images = DB::get()->posts->find([], $opts);
        $params = [
            'images' => $images,
            'image' => new Post()
        ];
        return $this->render('home', $params);
    }

    public function favorite(Request $request, Response $response) {
        $favPics = Application::$app->session->get('fav') ? Application::$app->session->get('fav') : [];
        foreach ($_POST['fav'] as $saved) {
            if (!in_array($saved, $favPics)) {
                $favPics[] = $saved;
            }
        }
        Application::$app->session->set('fav', $favPics);
        $response->redirect('/');
    }

    public function fav() {
        $favPosts = [];
        foreach (Application::$app->session->get('fav') as $favPic) {
            $img = DB::get()->posts->findOne([
                '_id' => new ObjectId($favPic)
            ]);
            $favPosts[] = $img;
        }
        $this->setLayout('main');
        return $this->render('fav', [
            'images' => $favPosts,
            'image' => new Post()
        ]);
    }

    public function favDelete(Request $request, Response $response) {
        $toDelete = $_POST['favDelete'];
        $newFav = [];
        foreach (Application::$app->session->get('fav') as $favPic) {
            if (!in_array($favPic, $toDelete)) {
                $newFav[] = $favPic;
            }
        }
        Application::$app->session->set('fav', $newFav);
        $response->redirect('/fav');
    }

    public function upload()
    {
        return $this->render('upload');
    }
}