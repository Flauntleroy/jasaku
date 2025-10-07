<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= isset($title) ? $title . ' | ' : '' ?>Jasa-Ku</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo/jasaku.png') ?>" />
    <link rel="alternate icon" href="<?= base_url('assets/css/favicon.ico') ?>" />
        <!-- TailAdmin Styles -->
        <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
    <style>[x-cloak]{display:none !important;}</style>
        <!-- Alpine.js for interactivity used by TailAdmin -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body
        x-data="{ page: 'ecommerce', loaded: true, darkMode: JSON.parse(localStorage.getItem('darkMode')) || false, stickyMenu: false, sidebarToggle: false, scrollTop: false }"
        x-init="$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
        :class="{ 'dark bg-gray-900': darkMode === true }"
    >
        <!-- Preloader Start -->
        <div
            x-show="loaded"
            x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 500) })"
            class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
        >
            <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
        </div>
        <!-- Preloader End -->

        <!-- Page Wrapper Start -->
        <div class="flex h-screen overflow-hidden">
