<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Configuración general
            [
                'key' => 'site_name',
                'value' => 'Moda Circular',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nombre del sitio',
            ],
            [
                'key' => 'site_description',
                'value' => 'Tienda de ropa minorista',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Descripción del sitio',
            ],

            // Configuración de WhatsApp
            [
                'key' => 'whatsapp_number',
                'value' => '5491112345678',
                'type' => 'text',
                'group' => 'whatsapp',
                'description' => 'Número de WhatsApp (formato: 549 + código de área + número)',
            ],
            [
                'key' => 'whatsapp_message_template',
                'value' => '¡Hola! Me gustaría hacer el siguiente pedido:',
                'type' => 'text',
                'group' => 'whatsapp',
                'description' => 'Mensaje inicial de WhatsApp',
            ],

            // Configuración de envíos
            [
                'key' => 'shipping_moto_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'shipping',
                'description' => 'Habilitar envío en moto',
            ],
            [
                'key' => 'shipping_moto_cost',
                'value' => '500',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Costo de envío en moto',
            ],
            [
                'key' => 'shipping_pickup_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'shipping',
                'description' => 'Habilitar retiro en persona',
            ],
            [
                'key' => 'shipping_pickup_address',
                'value' => 'Dirección del local',
                'type' => 'text',
                'group' => 'shipping',
                'description' => 'Dirección para retiro',
            ],

            // Métodos de pago
            [
                'key' => 'payment_mercadopago_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Habilitar Mercado Pago',
            ],
            [
                'key' => 'payment_transfer_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Habilitar transferencia bancaria',
            ],
            [
                'key' => 'payment_cash_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Habilitar efectivo',
            ],
            [
                'key' => 'payment_transfer_cbu',
                'value' => '',
                'type' => 'text',
                'group' => 'payment',
                'description' => 'CBU para transferencias',
            ],
            [
                'key' => 'payment_transfer_alias',
                'value' => '',
                'type' => 'text',
                'group' => 'payment',
                'description' => 'Alias para transferencias',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
