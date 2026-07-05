<?php

test('home page loads successfully', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Jelajahi Konser');
});
