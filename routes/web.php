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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('reset/password/api', 'Auth\ApiResetPassword')->name('api.reset.password');

Route::group(['namespace'=>'Admin', 'middleware'=>['auth']], function () {
    Route::get('/home',           'AdminController@index')->name('home');
    Route::resource('updates',    'UpdatesController');
    Route::get('/data/updates',   'UpdatesController@data')->name('data_updates');
    Route::resource('materials',  'MaterialsController');
    Route::get('/data/materials', 'MaterialsController@data')->name('data_materials');

});


/**
 * TEST REAl TIME APP
 */
Route::get('real-time/{no}', function (\Illuminate\Http\Request $request, $no) {
    if($no == 1){
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjhkNjZlNDg2NjBjOTE4OGI3MGQyNWYxNjZjYjEzZTM4ZTJlZjEwODQ3MjU3YmM5N2IwODQwODA4OGY5ODUwNDIxOTQzNTQwNjJiNGZmYzg4In0.eyJhdWQiOiIxIiwianRpIjoiOGQ2NmU0ODY2MGM5MTg4YjcwZDI1ZjE2NmNiMTNlMzhlMmVmMTA4NDcyNTdiYzk3YjA4NDA4MDg4Zjk4NTA0MjE5NDM1NDA2MmI0ZmZjODgiLCJpYXQiOjE1NTQyMTMyNzYsIm5iZiI6MTU1NDIxMzI3NiwiZXhwIjoxNTg1ODM1Njc2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Odmxlnnhny2nINxG4HsLt3LrRvYOu0Z3NMIEz5hExSVXY7SIqk9QKkADpsgp0CwG40DpemKRfPxLDiEtZAdZrE9S6z7oXcVqh2YqwFEd6O_vqRBVnq6F8dbbiFQh2KCnXRi7MawD7lAqfRs8olVB-0D_1JpHLVyKf5HVlJ0Q58Q0I28nXglwELtn9hi5fZr1nTQ4Fm-D-4o-K3h5fAh_MVO4vBlBNAcC6QlldIi6SXWkbvIwSkWttnV_EILYKmNGo1YuXirhNFGVzlRhFYWsLEOQQNf9XSaRiuE8oj1kK_j_JEwT_0X-03navzx-VMp9j3wjRh7pDWlnEk29jWOUYJ-pmU_4qmBi77O6-kW1yUvhTXziIWpYixDInJOOzDnI9dmGnbQ9UM3UteRafDMeK-romPko92GAa5qmUSK0iKu9obBsjVDUrc3A1kNe3Wio3LTb52lDpdDvkkX6S5mACXIKDITTkMGSQAm5U4EyHDjXsVpKBmhdCGJKdg4DKSxaEwvMWSLyc84Q38bh2DVgvOMZTn6vB2RQKmyoGzYnX9jvUlkhAaBwL3UGtaQHXHaan3dkAFwB1Zov73_11iDxAM86XUi-Y24laqtRlMolNTh6oMaNAEkQYw5Vh1APfqDDMqJ4MzhMKBO2XxYCdlaEhNUKFcfers_whm88y1lya6Y';
    }elseif($no == 2){
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNjZDJlNTRlNDI1M2E4YjBhYjRmZjdkMjFiZTI5MmQ2Y2QxMDRhMGUzMDUxOTU5NDgwMzhkMjc1ZmZlNmY3MThlMDEyY2VjN2Y1ZmJhMWZlIn0.eyJhdWQiOiIxIiwianRpIjoiY2NkMmU1NGU0MjUzYThiMGFiNGZmN2QyMWJlMjkyZDZjZDEwNGEwZTMwNTE5NTk0ODAzOGQyNzVmZmU2ZjcxOGUwMTJjZWM3ZjVmYmExZmUiLCJpYXQiOjE1NTQyMTMyNDksIm5iZiI6MTU1NDIxMzI0OSwiZXhwIjoxNTg1ODM1NjQ5LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.T7I9lIjJkttWkIPYLB9BlMXvEE7gcoCTnGD2LUrqUS7UEsNf31l0HF1q6KnmqGViPmyYJu0CHx_g8SeAIRAL6XFcNT-B2Twjwags2EgidmFY5dZtGgAOnIo8t4LBd5aAHPflthfiX6rt-kuwZ0kFU6UFXrSLVRSNn7NfWVPaF_b2YvTgU02nL0OEbEGf2CFKfdOdSxvbXLMRslxSUTycAX9Pg-9QbDOggtSt6HwfmcTkx0FmhYQBlsS-nbOBlp3MQYttyNYj2M5hMhUXFxx_2hEvxIHEhpplnjq7HG84XzSZX-XzVADcnW7DLd_1e4f576ZzepcRXqzHnaBswwdZoVPglZwZi7EfhWE7NGuSgjz9qhYcBfOqe7jWPmwDAiVnwbqHaA9OcLorB-oAEpfGnX0Er5Px0UxlRcsgWdzcsNXz4i-4mCtPz-INZJKE-km74s7GwKCQtphMy2-8E8k7US8hfvUNZKx8PzSx46qNqfUSg3CTztk4ytsc0XtoKY6IWp-OVNA21ZiPDm8Q8_2Mt4EjDxOEU-OFUk9Jc4Hmg0CWxYCyOQkc_Iyr146DC50nRc4Z3TvKPaiZPXzm66xKfJxQ8NOWcnUMFAcubwSNMQR1jjApQGgEx3xmGLEOoZiIbqhoVt7WVrOR3jnJTXx5sB0DKRkaJC7NNSBfyKL0Iro';
    }

    $user = $no;

    return view('test-real-time.index', compact('token', 'user'));
});
