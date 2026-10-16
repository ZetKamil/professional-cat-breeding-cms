<?php

use Illuminate\Support\Facades\Route;

test('registration is disabled for security', function () {
    $this->assertFalse(Route::has('register'));
    $this->assertFalse(Route::has('register.store'));

    $response = $this->get('/register');
    $response->assertNotFound();
});
