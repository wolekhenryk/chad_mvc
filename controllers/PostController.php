<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\DB;
use app\core\Request;
use app\core\Response;
use app\models\Post;

class PostController extends Controller
{
    public function uploadFile(Request $request) {
        $post = new Post();
        $this->setLayout('main');
        if ($request->isPost()) {
            $post->imgTitle = $request->getRequestData()['imgTitle'];
            $post->watermark = $request->getRequestData()['watermark'];
            $post->author = $request->getRequestData()['author'];
            if (Application::Auth() && $request->getRequestData()['private'] === 'priv') {
                $post->private = $request->getRequestData()['private'];
            }
            $post->fillData($_FILES['imgFile']);
            if ($post->validate()) {
                $post->uploadFile(round(microtime(true)), $post);
                $post->upload(round(microtime(true)));
            }
            return $this->render('upload', [
                'model' => $post
            ]);
        }
        return $this->render('upload', [
            'model' => $post
        ]);
    }

    public function paginateNext(Request $request, Response $response) {
        $page = Application::$app->session->get('page') ? Application::$app->session->get('page') : 1;
        if ($page < count(\app\core\DB::get()->posts->find()->toArray()) / 4) {
            Application::$app->session->set('page', ++$page);
        }
        $response->redirect('/');
    }

    public function paginatePrev(Request $request, Response $response)
    {
        $page = Application::$app->session->get('page') ? Application::$app->session->get('page') : 1;
        if ($page > 1) {
            Application::$app->session->set('page', --$page);
        }
        $response->redirect('/');
    }
}