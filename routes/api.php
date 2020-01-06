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
    Route::get('user/attributes/{id}','UserController@userattribs')->name('user.attributes');
    Route::get('employee/functiondata/{id}','EduFunctionDataController@functionDataForEmployee')->name('employee.functionData');
    Route::get('employee/activeOnly/1','EmployeeController@indexActive')->name('employee.active');
    Route::post('employee/archive/1','EmployeeController@archiveOldOrTADDEmployees')->name('employee.archiveOldOrTADDEmployees');
    Route::post('employee/toggleAllVisible/{toggle}','EmployeeController@toggleEmployeesVisibility')->name('employee.toggleAllVisible');
    Route::get('employee/interruptions/{id}','EmploymentInterruptionController@interruptionsForEmployee')->name('employee.interruptions');
    Route::get('employee/filterByName/{value}','EmployeeController@filterByName')->name('employee.filterByName');
    Route::get('ambt/availableForEmployee/{id}','EducationalFunctionController@availableForEmployee')->name('ambt.availableForEmployee');
    Route::get('educationalFunctionData/{id}/addEmployement/{employment_id}','EduFunctionDataController@addEmployement')->name('educationalFunctionData.addEmployement');
    Route::get('educationalFunctionData/{id}/removeEmployement/{employment_id}','EduFunctionDataController@removeEmployement')->name('educationalFunctionData.removeEmployement');
    Route::get('educationalFunctionData/tadd/fullIndex','EduFunctionDataController@fullIndex')->name('educationalFunctionData.fullindex');

    Route::get('educationalFunctionData/tadd/nextyear','EduFunctionDataController@nextYearTADD')->name('educationalFunctionData.nextyear');
    Route::get('educationalFunctionData/tadd/thisyear','EduFunctionDataController@thisYearTADD')->name('educationalFunctionData.thisyear');
    Route::get('educationalFunctionData/tadd/alreadytadd','EduFunctionDataController@alreadyTADD')->name('educationalFunctionData.alreadytadd');

    Route::post('educationalFunctionData/{id}/werkpunt','EduFunctionDataController@addWerkpunt')->name('educationalFunctionData.addWerkpunt');
    Route::delete('educationalFunctionData/{id}/werkpunt','EduFunctionDataController@verwijderWerkpunt')->name('educationalFunctionData.verwijderWerkpunt');
    Route::post('educationalFunctionData/{id}/tadd','EduFunctionDataController@addTADD')->name('educationalFunctionData.addTADD');
    Route::delete('educationalFunctionData/{id}/tadd','EduFunctionDataController@verwijderTADD')->name('educationalFunctionData.verwijderTADD');
    Route::get('settingsByContext/{context}','SettingsController@indexByContext')->name('settings.byContext');
    Route::patch('taddCalculator/updateSeniorityDays/{functionData_id}','TaddCalculationController@updateSeniorityDays')->name('taddCalculator.updateSeniorityDays');
    Route::patch('taddCalculator/updateAllSeniorityDays/{employee_id}','TaddCalculationController@updateAllSeniorityDays')->name('taddCalculator.updateAllSeniorityDays');

    Route::get('employee/pdf/{id}','PDFController@createPDF')->name('employee.createpdf');
});
