<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\user;
use app\models\LoginForm;

class AuthController extends Controller
{
    public function __construct() {
        $this->registerMiddleware(New AuthMiddleware(['profile']));
    }
    public function login(Request $request, Response $response)
    {
        $LoginForm = new LoginForm;
        if($request->isPost()){
            $LoginForm->loadData($request->getBody());
            if ($LoginForm->validate() && $LoginForm->login())
            {
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login',   [
            'model' => $LoginForm
        ]);
    }

    public function register(Request $request)
    {
        $user = new user();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            
            if($user->validate() && $user->save())    {
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
            }
            return $this->render('register', [
                'model' => $user
            ]);

        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function profile()    {
        return $this->render('profile');
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }
}