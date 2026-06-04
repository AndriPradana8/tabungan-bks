<?php

namespace App\Helpers;

class SuperadminMenuHelper
{
    public static function getMainNavItems()
    {
        return [
            [
                'icon' => 'box',
                'name' => 'Dashboard',
                'path' => '/superadmin/dashboard',
            ],
            [
                'icon' => 'users',
                'name' => 'Data Nasabah',
                'path' => '/superadmin/nasabah',
            ],
            [
                'icon' => 'wallet',
                'name' => 'Saldo Tabungan',
                'path' => '/superadmin/laporan/saldo',
            ],
            [
                'icon' => 'user-profile',
                'name' => 'Data Admin',
                'path' => '/superadmin/admin',
            ],
            [
                'icon' => 'bar-chart',
                'name' => 'Laporan',
                'subItems' => [
                    ['name' => 'Transaksi Harian',  'path' => '/superadmin/laporan/transaksi?periode=harian'],
                    ['name' => 'Transaksi Bulanan', 'path' => '/superadmin/laporan/transaksi?periode=bulanan'],
                    ['name' => 'Transaksi Tahunan', 'path' => '/superadmin/laporan/transaksi?periode=tahunan'],
                ],
            ],
            [
                'icon' => 'activity-log',
                'name' => 'Log Aktivitas',
                'path' => '/superadmin/activity-log',
            ],
            [
                'icon' => 'settings',
                'name' => 'Pengaturan',
                'path' => '/superadmin/pengaturan',
            ],
        ];
    }

    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menu',
                'items' => self::getMainNavItems()
            ]
        ];
    }
}
