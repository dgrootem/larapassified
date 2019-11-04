<?php

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.', 'middleware' => 'auth:api'], function () {
    Route::resource('school','SchoolController',['except' => ['create', 'edit']]);
    Route::resource('ambt','EducationalFunctionController',['except' => ['create', 'edit']]);
    Route::resource('user','UserController',['except' => ['create', 'edit']]);
    Route::resource('settings','SettingsController',['except' => ['create', 'edit']]);
    Route::resource('employee','EmployeeController',['except' => ['create', 'edit']]);
    Route::resource('employment','EmploymentController',['except' => ['create', 'edit']]);
    Route::resource('employmentInterruption','EmploymentInterruptionController',['except' => ['create', 'edit']]);
    Route::resource('educationalFunctionData','EduFunctionDataController',['except' => ['create', 'edit']]);
    Route::get('employee/functiondata/{id}','EduFunctionDataController@functionDataForEmployee')->name('employee.functionData');
    Route::get('employee/interruptions/{id}','EmploymentInterruptionController@interruptionsForEmployee')->name('employee.interruptions');
    Route::get('employee/filterByName/{value}','EmployeeController@filterByName')->name('employee.filterByName');
    Route::get('ambt/availableForEmployee/{id}','EducationalFunctionController@availableForEmployee')->name('ambt.availableForEmployee');
    Route::get('educationalFunctionData/{id}/addEmployement/{employment_id}','EduFunctionDataController@addEmployement')->name('educationalFunctionData.addEmployement');
    Route::get('educationalFunctionData/{id}/removeEmployement/{employment_id}','EduFunctionDataController@removeEmployement')->name('educationalFunctionData.removeEmployement');
});
