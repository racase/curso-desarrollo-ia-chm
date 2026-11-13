<?php

/*
 * Spanish validation messages for the rules the application uses.
 * Any rule missing here falls back to the English message.
 */

return [
    'exists' => 'El valor seleccionado en :attribute no es válido.',
    'in' => 'El valor seleccionado en :attribute no es válido.',
    'max' => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser un texto.',
    'unique' => 'Ya existe un registro con ese valor en :attribute.',
];
