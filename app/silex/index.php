<?php

use Symfony\Component\HttpFoundation\Request;

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/Application.php';

$app = \Application::bootstrap();

// Home
$app->get('/', function () use ($app) {
    return $app['twig']->render('layout.html.twig');
})->bind('home');

// SigIn
$app->get('/signin', function () use ($app) {
    return $app['twig']->render('signin.html.twig');
})->bind('signin');

$app->post('/signin', function (Request $request) use ($app) {
    $userRepository = $app['user_repository'];
    $service = new \Lw\Application\Service\User\SignInUserService($userRepository);
    $service->execute(
        $request->get('email'),
        $request->get('password')
    );

    return $app->redirect('/login');
});

// Login
$app->get('/login', function () use ($app) {
    return $app['twig']->render('login.html.twig');
})->bind('login');

$app->post('/login', function (Request $request) use ($app) {
    $userRepository = $app['user_repository'];
    $session = $app['session'];

    $authentifier = new \Lw\Infrastructure\Domain\SessionAuthentifier($userRepository, $session);
    $service = new \Lw\Application\Service\User\LogInUserService($authentifier);
    $result = $service->execute($request->get('email'), $request->get('password'));

    return $result ? $app->redirect('/') : $app->redirect('/login');
});

// Logout
$app->get('/logout', function () use ($app) {
    $userRepository = $app['user_repository'];
    $session = $app['session'];
    $authentifier = new \Lw\Infrastructure\Domain\SessionAuthentifier($userRepository, $session);
    $service = new \Lw\Application\Service\User\LogOutUserService($authentifier);
    $result = $service->execute();
    return $app->redirect('/login');
})->bind('logout');

// View stories
$app->get('/dashboard', function () use ($app) {


    $usecase = new \Lw\Application\Service\User\ViewWishesService($app['wish_repository']);
    $response = $usecase->execute();

    return $app['twig']->render('view-stories.html.twig', ['stories' => $response->stories]);
})->bind('read');

// Add story
$app->get('/story/add', function () use ($app) {
    return $app['twig']->render('add-story.html.twig');
})->bind('add-story');

// View story
$app->get('/story/{id}', function ($id) use ($app) {
    $request = new \Cyoa\UseCases\Story\ViewStoryRequest();
    $request->storyId = $id;

    $response = (new \Cyoa\UseCases\Story\ViewStoryUseCase($app['story-repository']))->execute($request);

    return $app['twig']->render('view-story.html.twig', ['story' => $response->story]);
})->bind('story');

$app->post('/wish/add', function (Request $httpRequest) use ($app) {
    $request = new \Cyoa\UseCases\Story\CreateStoryRequest();
    $request->title = $httpRequest->get('title');
    $request->description = $httpRequest->get('description');

    $storyRepository = $app['em']->getRepository('Cyoa\Domain\Story');
    $usecase = new \Cyoa\UseCases\Story\CreateStoryUseCase($storyRepository);
    $response = $usecase->execute($request);

    return $app->redirect('/stories');
});

$app->get('/page/{id}', function ($id) use ($app) {
    $request = new \Cyoa\UseCases\Page\ViewPageRequest();
    $request->pageId = $id;

    $pageRepository = $app['em']->getRepository('Cyoa\Domain\Page');
    $usecase = new \Cyoa\UseCases\Page\ViewPageUseCase($pageRepository);
    $response = $usecase->execute($request);

    return $app['twig']->render('view-page.html.twig', ['page' => $response->page]);
})->bind('page');

$app->get('/dashboard', function () use ($app) {
    return $app['twig']->render('dashboard.html.twig');
})->bind('dashboard');

$app->run();
