<?php

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

Route::feeds();

Route::get('/', 'HomeController')->name('front.home');

Route::get('aides', 'AidesController')->name('front.aides');

Route::get('mecenat', 'MecenatController')->name('front.mecenat');

Route::get('actualites/precedentes', 'NewsController')->name('front.news.before');

Route::post('newsletter/subscribe', 'SubscribeNewsletterController')->name('front.newsletter.subscribe');

Route::view('contact', 'front.contact')->name('front.contact');
Route::post('contact', 'ContactController@store')->name('front.contact.store');

Route::view('mentions-legales', 'front.legal-notice')->name('front.legal-notice');
Route::view('accessibilite', 'front.accessibility')->name('front.accessibility');

Route::view('publier', 'front.publish')->name('front.publish');

Route::get('aides/consulter', 'AidesController')->name('front.aides.consulter');
Route::view('aides/qui-sommes-nous', 'front.aides.about-us')->name('front.aides.about-us');

Route::get('dispositifs/{closed?}', 'CallForProjectsController@index')->name('front.dispositifs');
Route::get('dispositifs/detail/{slug}', 'CallForProjectsController@unique')->name('front.dispositifs.unique');

Route::get('recherche', 'SearchController')->name('front.search');

// Exports
Route::get('export/{table}/csv', 'ExportController@table')->name('export.csv');

Route::get('export/dispositifs/pdf', 'ExportController@dispositifsPdf')->name('export.pdf');
Route::get('export/dispositifs/xlsx', 'ExportController@dispositifsXlsx')->name('export.xlsx');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
})->name('front.error');

if (app()->environment() === 'local') {
    Route::get('test-email', function () {

        $news = App\CallForProjects::with([
            'thematic',
            'subthematic',
            'projectHolders',
            'perimeters',
            'beneficiaries'
        ])->ofTheWeek()->orderBy('updated_at', 'desc')->get();

        return view('emails.mailchimp.new-calls-for-projects', ['callsOfTheWeek' => $news]);
    });
}
