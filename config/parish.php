<?php

return [
    'admin' => [
        'name' => env('PARISH_ADMIN_NAME', 'Administrador Paroquial'),
        'username' => env('PARISH_ADMIN_USERNAME', 'admin'),
        'email' => env('PARISH_ADMIN_EMAIL', 'admin@paroquia.local'),
        'password' => env('PARISH_ADMIN_PASSWORD', 'Paroquia@2026'),
    ],
    'volunteer_author' => [
        'name' => env('VOLUNTEER_AUTHOR_NAME', 'Cristian Oliveira'),
        'github_url' => env('VOLUNTEER_GITHUB_URL', 'https://github.com/cristianoliveira30'),
        'linkedin_url' => env('VOLUNTEER_LINKEDIN_URL', 'https://www.linkedin.com/in/cristian-oliveira-317b0b20b/'),
        'pix_key' => env('VOLUNTEER_PIX_KEY', 'apoio@exemplo.com'),
    ],
];
