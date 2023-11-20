<?php

namespace App\Services;

class MenuServices
{
    public static function call()
    {
        return new self();
    }

    public function menus()
    {
        return [
            [
                'label' => 'Menu Utama',
                'items' => [
                    [
                        'label' => 'Beranda',
                        'icon' => 'bi:grid-fill',
                        'to' => route('dashboard'),
                        'allowed' => true,
                        'active' => request()->routeIs('dashboard')
                    ],
                    [
                        'label' => 'Merchant',
                        'icon' => 'mdi:store-settings-outline',
                        'to' => route('merchant.content.index'),
                        'allowed' => true,
                        'active' => request()->routeIs(['merchant.content.*'])

                    ],
                ]
            ],
        ];
    }
}
