<?php

return [
    'title' => [
        'index' => 'Listado de proyectos'
    ],
    'filters' => [
        'status' => ['all' => 'Todos', 'finished' => 'Terminado', 'ongoing' => 'Pendiente'],
        'deadline' => ['all' => 'Todos', 'current' => 'Dentro del plazo', 'expired' => 'Fuera de plazo'],
    ],
    'forms' => [
        'status' => ['1' => 'Terminado', '0' => 'Pendiente']
    ]
];
