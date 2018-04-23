<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20/05/2016
 * Time: 3:17 PM
 */


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . "/vendor/autoload.php";

$app = new \Silex\Application();

date_default_timezone_set("UTC");


$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views',
    'twig.options' => [
        'cache' => __DIR__ . '/cache',
    ],
]);


$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/logs/development.log',
));

$app->get("/", function () use ($app) {

    return $app['twig']->render("home.twig", ['activepage' => 'home']);
});

$app->get("/call-for-papers/", function () use ($app) {

    return $app['twig']->render("cfpapers.twig", ['activepage' => 'papers']);
});

$app->get("/call-for-posters/", function () use ($app) {

    return $app['twig']->render("cfposters.twig", ['activepage' => 'posters']);
});

$app->get("/organizers/", function () use ($app) {

    return $app['twig']->render("organizers.twig", ['activepage' => 'organizers']);
});

$app->get("/submissions/", function () use ($app) {

    return $app['twig']->render("submissions.twig", ['activepage' => 'submissions']);
});
$app->get("/localinfo/", function () use ($app) {

    return $app['twig']->render("localinfo.twig", ['activepage' => 'localinfo']);
});

$app->get("/registration/", function () use ($app) {

    return $app['twig']->render("registration.twig", ['activepage' => 'registration']);
});


//$app->get("/program/", function () use ($app) {
//
//    $papers = json_decode(file_get_contents(__DIR__ . "/data/dev7-data.json"), true);
//    $posters = json_decode(file_get_contents(__DIR__ . "/data/dev16posters-data.json"), true);
//
//
//    $params = compact('papers', 'posters');
//
//    $params['activepage'] = 'program';
//    return $app['twig']->render("program2.twig", $params);
//});

$app->get("/program/", function () use ($app) {

    $papers = json_decode(file_get_contents(__DIR__ . "/data/dev7-data.json"), true);
    $posters = json_decode(file_get_contents(__DIR__ . "/data/dev16posters-data.json"), true);


    $params = compact('papers', 'posters');

    $params['activepage'] = 'program';
    return $app['twig']->render("program3.twig", $params);
});

$app->get("/{category}/{id}/{title}", function ($category, $id, $title) use ($app) {

    $papers = json_decode(file_get_contents(__DIR__ . "/data/dev7-data.json"), true);
    $posters = json_decode(file_get_contents(__DIR__ . "/data/dev16posters-data.json"), true);

    $paper = null;
    if ($category == 'papers') {
        $paper = $papers[$id - 1];
    } else if ($category == 'posters') {
        $paper = $posters[$id - 1];
    }

    $params = compact('paper');
    $params['activepage'] = 'program';
    return $app['twig']->render("paper-summary.twig", $params);
});

$app->get("/bitange-ndemo-bio/", function () use ($app) {


    return $app['twig']->render("bitange-bio.twig", ['activepage' => '']);
});

$app->get("/schedule/", function () use ($app) {


    return $app['twig']->render("schedule.twig", ['activepage' => 'Schedule']);
});


$app['debug'] = true;

//if ($app['debug'] != true) {
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 404:
            return $app['twig']->render("404.twig", ['activepage' => 'Not Found']);
        default:
            $app['monolog']->addDebug($e->getTraceAsString());

            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});
//}


$app->run();

