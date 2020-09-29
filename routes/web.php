<?php

Route::get('/', 'AuthController@signin');

Route::get('/hello', 'TestController@hello');

Route::get('/phpinfo', 'TestController@info');

Route::get('/calauth', 'MSGraphController@calendarAuth');

Route::get('/junior/links/{id}', 'JuniorController@getLinks');

Route::get('/junior/image/{code}', 'JuniorController@getImage');

Route::get('/junior', 'JuniorController@home');

Route::post('staff/quicklink', 'StaffHomeController@addLink');

Route::delete('staff/quicklink', 'StaffHomeController@deleteLink');

Route::get('/staff/hallpass', 'HallpassController@show');

Route::post('/staff/hallpass', 'HallpassController@startPass');

Route::put('/staff/hallpass', 'HallpassController@finishPass');

Route::get('/staff/forms', 'FormController@show');

Route::post('/staff/forms/pd', 'FormPDController@new');

Route::post('/staff/forms/excur', 'FormExcurController@new');

Route::delete('/staff/forms/{type}/{id}', 'FormController@archive');

Route::delete('/staff/forms/excur/{id}/file/{fileId}', 'FormExcurController@deleteFile');

Route::post('/staff/forms/relief/{staffId?}', 'FormController@getRelief');

Route::post('/staff/forms/reliefall', 'FormController@getAllRelief');

Route::post('/staff/forms/students/{type}', 'FormController@getStudents');

Route::post('/staff/forms/studentslist', 'FormController@getStudentlist');

Route::post('/staff/forms/upload/{id}', 'FormController@uploadFile');

Route::get('/staff/forms/riskrow', 'FormController@getRiskRow');

Route::get('/staff/forms/carerow', 'FormController@getCareRow');

Route::get('/staff/forms/staffrow/{id}/{type}', 'FormController@getStaffRow');

Route::get('/staff/forms/studentrow/{type}', 'FormController@getStudentRow');

Route::get('/staff/forms/expenserow/{id}', 'FormController@getExpenseRow');

Route::get('/staff/forms/reliefrow/{rownum}', 'FormController@getReliefRow');

Route::get('/staff/forms/pd/{id}/pdf', 'FormPdController@getPDF');

Route::get('/staff/forms/pd/{id}/{page?}', 'FormPDController@showPd');

Route::get('/staff/forms/excur/file/{id}', 'FormExcurController@getFile');

Route::get('/staff/forms/excur/{id}/pdf', 'FormExcurController@getPDF');

Route::get('/staff/forms/excur/{id}/{page?}', 'FormExcurController@show');

Route::post('/staff/forms/pd/{id}/{page}', 'FormPDController@setPd');

Route::post('/staff/forms/excur/{id}/{page}', 'FormExcurController@set');

Route::get('/staff/recent', 'MSGraphController@getRecentFiles');

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

Route::get('/student/class', 'StudentHomeController@getHomework')->middleware('msauth','checkstudent');

Route::get('/student/assess', 'StudentHomeController@getAssessments')->middleware('msauth','checkstudent');

Route::get('/student/points', 'StudentHomeController@getHousepoints')->middleware('msauth','checkstudent');

Route::get('/student/timetable/{date}', 'StudentHomeController@getTimetable')->middleware('msauth','checkstudent');

Route::get('/student', 'StudentHomeController@show')->middleware('msauth','checkstudent');

Route::get('/staff/timetable/{date}', 'StaffHomeController@getTimetable')->middleware('msauth','checkstaff');

Route::get('/staff/events/{date}', 'StaffHomeController@getEvent');

Route::get('/staff', 'StaffHomeController@show')->middleware('msauth','checkstaff')->name('staffhome');

Route::get('/signin', 'AuthController@signin');

Route::get('/callback', 'AuthController@callback');

Route::get('/signout', 'AuthController@signout');

Route::get('/test', 'TestController@test');

Route::post('/admin/bookings/category', 'BookingController@storeCategory')->middleware('msauth','checkstaff');

Route::delete('/admin/bookings/category', 'BookingController@destroyCategory')->middleware('msauth','checkstaff');

Route::post('/admin/bookings/item', 'BookingController@storeItem')->middleware('msauth','checkstaff');

Route::delete('/admin/bookings/item', 'BookingController@destroyItem')->middleware('msauth','checkstaff');

Route::get('/admin/bookings/', 'BookingController@show')->middleware('msauth','checkstaff');

Route::post('/admin/forms/pd/pdf', 'FormPDController@downloadPdf');

Route::get('/admin/forms/pd/{id}', 'FormPDController@showAdmin');

Route::get('/admin/forms/excur/{id}', 'FormExcurController@showAdmin');

Route::post('/admin/forms/excur/{id}/approve', 'FormExcurController@approveForm');

Route::post('/admin/forms/pd/{id}/approve', 'FormPDController@approveForm');

Route::get('/admin/forms', 'FormController@showAdmin');

Route::get('/admin/terms/', 'TermController@index')->middleware('msauth','checkstaff');

Route::post('/admin/terms/', 'TermController@store')->middleware('msauth','checkstaff');

Route::delete('/admin/terms/', 'TermController@destroy')->middleware('msauth','checkstaff');

Route::get('/admin/duty/', 'DutyController@locationIndex')->middleware('msauth','checkstaff');

Route::post('/admin/duty/', 'DutyController@storeLocation')->middleware('msauth','checkstaff');

Route::delete('/admin/duty/', 'DutyController@deleteLocation')->middleware('msauth','checkstaff');

Route::get('/events', 'MSGraphController@getUserEvents');


