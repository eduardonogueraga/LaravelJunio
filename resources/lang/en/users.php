<?php

return [
    'title' => [
        'index' => 'Listado de usuarios',
        'trash' => 'Papelera de usuarios',
    ],
    'roles' => [
        'admin' => 'Admin',
        'user' => 'Usuario'
    ],
    'states' => ['active' => 'Activo', 'inactive' => 'Inactivo'],
    'filters' => [
        'roles' => ['all' => 'Rol', 'admin' => 'Administradores', 'user' => 'Usuarios'],
        'states' => ['all' => 'Todos', 'active' => 'Solo activos', 'inactive' => 'Solo inactivos'],
        'twitter' => ['all'=> 'Todos', 'with' => 'Con cuenta', 'without'=> 'Sin cuenta'],
        'occupation' => ['all' => 'Todos', 'employed' => 'Con trabajo', 'unemployed' => 'Sin trabajo'],
    ],
];
