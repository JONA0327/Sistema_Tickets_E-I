<?php

use App\Models\Ticket;

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

$tickets = Ticket::all(['id', 'folio', 'estado', 'correo_solicitante']);

foreach ($tickets as $ticket) {
    echo "ID: {$ticket->id} - Folio: {$ticket->folio} - Estado: {$ticket->estado} - Email: {$ticket->correo_solicitante}\n";
}