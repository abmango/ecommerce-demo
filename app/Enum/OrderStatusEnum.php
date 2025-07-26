<?php

namespace App\Enum;

class OrderStatusEnum {

    const PENDIENTE = 'pendiente';
    const CONFIRMADA = 'confirmada';
    const RECHAZADA = 'rechazada';

    // agrego estado nuevo para futuras implementaciones
    const CANCELADA = 'cancelada_por_cliente';

}