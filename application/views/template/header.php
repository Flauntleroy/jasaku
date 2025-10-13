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
        
        <!-- DataTables CSS -->
        <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.4/af-2.7.1/b-3.2.5/b-colvis-3.2.5/b-html5-3.2.5/b-print-3.2.5/cr-2.1.1/cc-1.1.0/date-1.6.0/fc-5.0.5/fh-4.0.4/kt-2.12.1/r-3.0.7/rg-1.6.0/rr-1.5.0/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.2/sr-1.4.2/datatables.min.css" rel="stylesheet">
    <style>[x-cloak]{display:none !important;}</style>
 
        <!-- PDF Make -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
        
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.3.4/af-2.7.1/b-3.2.5/b-colvis-3.2.5/b-html5-3.2.5/b-print-3.2.5/cr-2.1.1/cc-1.1.0/date-1.6.0/fc-5.0.5/fh-4.0.4/kt-2.12.1/r-3.0.7/rg-1.6.0/rr-1.5.0/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.2/sr-1.4.2/datatables.min.js" integrity="sha384-klVXicbFbbL7o9XmV7xf6809J2aTM61RarSHZEjg3tUAeZlQah25I+7qsFgebuPw" crossorigin="anonymous"></script>
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
