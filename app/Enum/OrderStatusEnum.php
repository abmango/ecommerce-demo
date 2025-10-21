<?php

namespace App\Enum;

class OrderStatusEnum {

    const PENDIENTE = [
        'key' => 'pendiente',
        'value' => 'Pendiente'
    ];
    const CONFIRMADA = [
        'key' => 'confirmada',
        'value' => 'Confirmada'
    ];
    const RECHAZADA = [
        'key' => 'rechazada',
        'value' => 'Rechazada'
    ];
    // agrego estado nuevo para futuras implementaciones
    const CANCELADA = [
        'key' => 'cancelada_por_cliente',
        'value' => 'Cancelada por el cliente'
    ];

    public static function all() {      

        $data = [];

        $data[self::PENDIENTE['key']] = self::PENDIENTE['value'];
        $data[self::CONFIRMADA['key']] = self::CONFIRMADA['value'];
        $data[self::RECHAZADA['key']] = self::RECHAZADA['value'];
        $data[self::CANCELADA['key']] = self::CANCELADA['value'];

        return (array)$data;
    }

}