<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pages = [
    'index.html' => 'customer.home',
    'services.html' => 'customer.services',
    'booking.html' => 'customer.booking',
    'technicians.html' => 'customer.technicians',
    'estimate.html' => 'customer.estimate',
    'location.html' => 'customer.location',
    'tracking.html' => 'customer.tracking',
    'order-history.html' => 'customer.order-history',
    'profile.html' => 'customer.profile',
    'partner-dashboard.html' => 'partner.dashboard',
    'partner-orders.html' => 'partner.orders',
    'partner-active-job.html' => 'partner.active-job',
    'admin-dashboard.html' => 'admin.dashboard',
    'admin-orders.html' => 'admin.orders',
];

$replacements = [
    'http://localhost:8000/assets/' => 'public/assets/',
    'http://localhost/assets/' => 'public/assets/',
    '/assets/' => 'public/assets/',
    'http://localhost:8000/customer/services' => 'services.html',
    'http://localhost:8000/customer/booking' => 'booking.html',
    'http://localhost:8000/customer/technicians' => 'technicians.html',
    'http://localhost:8000/customer/estimate' => 'estimate.html',
    'http://localhost:8000/customer/location' => 'location.html',
    'http://localhost:8000/customer/tracking' => 'tracking.html',
    'http://localhost:8000/customer/order-history' => 'order-history.html',
    'http://localhost:8000/customer/profile' => 'profile.html',
    'http://localhost:8000/partner/dashboard' => 'partner-dashboard.html',
    'http://localhost:8000/partner/orders' => 'partner-orders.html',
    'http://localhost:8000/partner/active-job' => 'partner-active-job.html',
    'http://localhost:8000/admin/dashboard' => 'admin-dashboard.html',
    'http://localhost:8000/admin/orders' => 'admin-orders.html',
    'http://localhost:8000/#cara-kerja' => 'index.html#cara-kerja',
    'http://localhost:8000/#faq' => 'index.html#faq',
    'http://localhost:8000' => 'index.html',
    'href="/customer/services"' => 'href="services.html"',
    'href="/customer/booking"' => 'href="booking.html"',
    'href="/customer/technicians"' => 'href="technicians.html"',
    'href="/customer/estimate"' => 'href="estimate.html"',
    'href="/customer/location"' => 'href="location.html"',
    'href="/customer/tracking"' => 'href="tracking.html"',
    'href="/customer/order-history"' => 'href="order-history.html"',
    'href="/customer/profile"' => 'href="profile.html"',
    'href="/partner/dashboard"' => 'href="partner-dashboard.html"',
    'href="/partner/orders"' => 'href="partner-orders.html"',
    'href="/partner/active-job"' => 'href="partner-active-job.html"',
    'href="/admin/dashboard"' => 'href="admin-dashboard.html"',
    'href="/admin/orders"' => 'href="admin-orders.html"',
    'href="/"' => 'href="index.html"',
];

foreach ($pages as $file => $view) {
    try {
        $html = view($view)->render();
        foreach ($replacements as $from => $to) {
            $html = str_replace($from, $to, $html);
        }
        file_put_contents(__DIR__ . '/' . $file, $html);
        echo "Generated: {$file}\n";
    } catch (\Throwable $e) {
        echo "Error on {$view}: " . $e->getMessage() . "\n";
    }
}
echo "ALL PAGES EXPORTED SUCCESSFULLY!\n";
