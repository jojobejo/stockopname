<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Portal extends CI_Controller
{
    public function index()
    {
        $apps = [
            [
                'name' => 'KIU PO',
                'url' => 'https://kiupo.karismaerp.com',
                'icon' => 'fa-shopping-cart',
                'description' => 'Purchase Order & Procurement Management.',
                'accent' => '#2563eb',
            ],
            [
                'name' => 'Warehouse',
                'url' => 'https://kiuwarehouse.karismaerp.com',
                'icon' => 'fa-warehouse',
                'description' => 'Warehouse Management System.',
                'accent' => '#0f766e',
            ],
            [
                'name' => 'Penilaian Lingkungan Kerja',
                'url' => 'https://karismaerp.com/auth',
                'icon' => 'fa-leaf',
                'description' => 'Work Environment Assessment & Monitoring.',
                'accent' => '#16a34a',
            ],
            [
                'name' => 'KMT CORN',
                'url' => 'https://kmtcorn.karismaerp.com',
                'icon' => 'fa-seedling',
                'description' => 'Sales & Operational Management.',
                'accent' => '#d97706',
            ],
            [
                'name' => 'KPI',
                'url' => 'https://kpi.karismaerp.com',
                'icon' => 'fa-chart-line',
                'description' => 'Employee Performance Dashboard.',
                'accent' => '#4338ca',
            ],
            [
                'name' => 'POS Security',
                'url' => 'https://hrd.karismaerp.com',
                'icon' => 'fa-shield-alt',
                'description' => 'Security Activity Journal.',
                'accent' => '#0ea5e9',
            ],
            [
                'name' => 'Katalog Produk',
                'url' => 'https://kiukatalog.karismaerp.com',
                'icon' => 'fa-book-open',
                'description' => 'Digital Product Catalog.',
                'accent' => '#7c3aed',
            ],
            [
                'name' => 'Karisma Online',
                'url' => 'https://karismaonline.karismaerp.com',
                'icon' => 'fa-shopping-bag',
                'description' => 'Online Sales Platform.',
                'accent' => '#db2777',
                'hidden' => true,
            ],
            [
                'name' => 'Plafon Sales',
                'url' => 'https://karismaonline.karismaerp.com',
                'icon' => 'fa-credit-card',
                'description' => 'Customer Credit Limit Monitoring.',
                'accent' => '#0284c7',
            ],
            [
                'name' => 'Stockopname',
                'url' => 'https://stockopname.karismaerp.com',
                'icon' => 'fa-boxes',
                'description' => 'Stock counting and inventory reconciliation.',
                'accent' => '#14b8a6',
            ],
        
        ];

        $apps = array_values(array_filter($apps, function ($app) {
            return empty($app['hidden']);
        }));

        $data = [
            'apps' => $apps,
            'app_count' => count($apps),
        ];

        $this->load->view('portal/index', $data);
    }
}
