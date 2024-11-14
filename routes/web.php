<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CreateGame;

/*
Route::view('/', 'welcome')
    ->name('welcome');



Route::view('event/{eventId}', 'welcome')
    ->name('view-events');
*/

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('games', 'games')
    ->middleware(['auth', 'verified'])
    ->name('games');

Route::view('/', 'events')
    ->name('welcome-event');

Route::view('events', 'events')
    ->name('events');

Route::view('events/{eventId}', 'events')
    ->name('view-event');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('schedule', 'schedule')
        ->name('schedule');

    Route::view('games/{bggId}', 'games')
        ->name('game-details');
    
    Route::view('events/{eventId}/edit', 'events')
        ->name('edit-event');
});
        
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');




require __DIR__.'/auth.php';
