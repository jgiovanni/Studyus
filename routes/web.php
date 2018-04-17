<?php

use Artesaos\SEOTools\Facades\SEOTools as SEO;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$dispatcher = app('Dingo\Api\Dispatcher');
Auth::routes();

$this->get('/', function () {
    SEO::setTitle('STUDYUS');
    return view('site.layouts.home');
});

$this->group(['prefix' => 'app'], function () {
    $this->get('/', function () {
        SEO::setTitle('Dashboard');
        return view('app.instructors.dashboard');
    });

    $this->group(['prefix' => 'classrooms'], function ($id) {
        $this->get('/', function () {
            SEO::setTitle('Classrooms');
            return view('app.instructors.classrooms.index');
        });
        $this->get('{id}', function ($id) {
            $classroom = App\ClassRoom::whereId($id)->orWhere('url_id', $id)->firstOrFail();
            SEO::setTitle('Classrooms: ' . $classroom->name);
            return view('app.instructors.classrooms.show', compact('classroom'));
        });
    });

    $this->group(['prefix' => 'assignments'], function ($id) {
        $this->get('/', function () {
            SEO::setTitle('Assignments');
            return view('app.instructors.assignments.index');
        });
        $this->get('{id}', function ($id) {
            $task = App\Task::whereId($id)->orWhere('url_id', $id)->firstOrFail();
            SEO::setTitle('Assignments: ' . $task->name);
            return view('app.instructors.assignments.show', compact('task'));
        });
        $this->get('{id}/preview', function ($id) {
            $task = App\Task::whereId($id)->orWhere('url_id', $id)->firstOrFail();
            SEO::setTitle('Assignment Preview: ' . $task->name);
            return view('app.instructors.assignments.preview', compact('task'));
        });
    });


});

$this->group(['prefix' => '/vendor/froala-editor/js/plugins/wiris/integration'], function () {
    $this->get('configurationjs.php', function () {
//    ob_start();
        include public_path('vendor/froala-editor/js/plugins/wiris/integration/configurationjs.php');
//    return ob_get_clean();
    });
    $this->get('configurationjson.php', function () {
//    ob_start();
        include public_path('vendor/froala-editor/js/plugins/wiris/integration/configurationjson.php');
//    return ob_get_clean();
    });

    $this->get('showimage.php', function () {
        include public_path('vendor/froala-editor/js/plugins/wiris/integration/showimage.php');
    });

    $this->post('showimage.php', function () {
        include public_path('vendor/froala-editor/js/plugins/wiris/integration/showimage.php');
    });

    $this->post('service.php', function () {
        include public_path('vendor/froala-editor/js/plugins/wiris/integration/service.php');
    });

});


Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();