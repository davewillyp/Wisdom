<?php

Route::get('/', 'AuthController@signin');

Route::get('/phpinfo', 'TestController@info');

Route::get('/staff/papercut', 'PapercutController@getAccountBalances');

Route::get('/staff/notices', 'StaffHomeController@getNotices');

Route::get('/staff/duty/user/{user}', 'DutyController@userSearch')->middleware('msauth','checkstaff');

Route::get('/staff/duty/{day?}', 'DutyController@show')->name('duty');

Route::post('/staff/duty', 'DutyController@storeUser');

Route::post('/staff/bookings', 'BookingController@store');

Route::delete('/staff/bookings', 'BookingController@destroy');

Route::get('/staff/bookings/delete/{id}/{recurr}', 'BookingController@recurr');

Route::get('/staff/bookings/items', 'BookingController@getitems');

Route::get('/staff/bookings/{date?}', 'BookingController@index')->middleware('msauth','checkstaff')->name('bookings');

Route::get('/staff/calendar/create/{date}', 'CalendarController@create');

Route::post('/staff/calendar/create', 'CalendarController@store');

Route::delete('/staff/calendar', 'CalendarController@destroy');

Route::get('/staff/calendar/{term?}', 'CalendarController@show')->name('calendar');

Route::get('/student/timetable/{date}', 'StudentHomeController@timetable')->middleware('msauth','checkstudent');

Route::get('/student', 'StudentHomeController@show')->middleware('msauth','checkstudent');

Route::get('/staff/timetable/{date}', 'StaffHomeController@getTimetable')->middleware('msauth','checkstaff');

Route::get('/staff/events/{weekid?}', 'StaffHomeController@getEvents')->middleware('msauth','checkstaff');

Route::get('/staff', 'StaffHomeController@show')->middleware('msauth','checkstaff');

Route::get('/signin', 'AuthController@signin');

Route::get('/callback', 'AuthController@callback');

Route::get('/signout', 'AuthController@signout');

Route::get('/test', 'TestController@test');

Route::post('/admin/bookings/category', 'BookingController@storeCategory')->middleware('msauth','checkstaff');

Route::delete('/admin/bookings/category', 'BookingController@destroyCategory')->middleware('msauth','checkstaff');

Route::post('/admin/bookings/item', 'BookingController@storeItem')->middleware('msauth','checkstaff');

Route::delete('/admin/bookings/item', 'BookingController@destroyItem')->middleware('msauth','checkstaff');

Route::get('/admin/bookings/', 'BookingController@show')->middleware('msauth','checkstaff');

Route::get('/admin/terms/', 'TermController@index')->middleware('msauth','checkstaff');

Route::post('/admin/terms/', 'TermController@store')->middleware('msauth','checkstaff');

Route::delete('/admin/terms/', 'TermController@destroy')->middleware('msauth','checkstaff');

Route::get('/admin/duty/', 'DutyController@locationIndex')->middleware('msauth','checkstaff');

Route::post('/admin/duty/', 'DutyController@storeLocation')->middleware('msauth','checkstaff');

Route::delete('/admin/duty/', 'DutyController@deleteLocation')->middleware('msauth','checkstaff');

Route::get('/events', 'MSGraphController@getUserEvents');

