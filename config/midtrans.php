<?php

return [
    [
        'enable' => true,
        'payment_type' => 'qris',
        'key' => 'qris',
        'name' => 'QRIS',
        'description' => 'Generate QRIS dinamis untuk setiap pembayaran',
        'image' => 'images/banks/qris.png',
        'payload' => [
          'qris' => 
          [
            'acquirer' => 'gopay',
          ],
        ],
    ],
    [
        'enable' => true,
        'payment_type' => 'bank_transfer',
        'key' => 'bca',
        'name' => 'Virtual Account BCA',
        'description' => 'Bayar ke Virtual Account BCA',
        'image' => 'images/banks/bca-square.png',
        'payload' => [
          'bank_transfer' => 
          [
            'bank' => 'bca',
          ],
        ],
    ],
    [
        'enable' => false,
        'payment_type' => 'bank_transfer',
        'key' => 'bni',
        'name' => 'Virtual Account BNI',
        'description' => 'Bayar ke Virtual Account BNI',
        'image' => 'images/banks/bni-square.png',
        'payload' => [
          'bank_transfer' => 
          [
            'bank' => 'bni',
          ],
        ],
    ],
    [
        'enable' => true,
        'payment_type' => 'bank_transfer',
        'key' => 'bri',
        'name' => 'Virtual Account BRI',
        'description' => 'Bayar ke Virtual Account BRI',
        'image' => 'images/banks/bri-square.png',
        'payload' => [
          'bank_transfer' => 
          [
            'bank' => 'bri',
          ],
        ],
    ],
    [
        'enable' => true,
        'payment_type' => 'bank_transfer',
        'key' => 'cimb',
        'name' => 'Virtual Account CIMB',
        'description' => 'Bayar ke Virtual Account CIMB',
        'image' => 'images/banks/cimb-square.png',
        'payload' => [
          'bank_transfer' => 
          [
            'bank' => 'cimb',
          ],
        ],
    ],
    [
        'enable' => true,
        'payment_type' => 'bank_transfer',
        'key' => 'permata',
        'name' => 'Virtual Account Bank Permata',
        'description' => 'Bayar ke Virtual Account Bank Permata',
        'image' => 'images/banks/permata-square.png',
        'payload' => [
          'bank_transfer' => 
          [
            'bank' => 'permata',
          ],
        ],
    ],
];
