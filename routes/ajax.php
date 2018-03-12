<?php

Route::resource('clientes', 'ClientController');
Route::resource('projetos', 'ProjectController');
Route::post('fastway-tarefas', 'FastWayController@new');
