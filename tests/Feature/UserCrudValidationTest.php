<?php

test('user crud validation', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});
