<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Header -->
<header x-data="{menuToggle: false}" class="sticky top-0 z-99999 flex w-full border-gray-200 bg-white lg:border-b dark:border-gray-800 dark:bg-gray-900">
  <div class="flex grow flex-col items-center justify-between lg:flex-row lg:px-6">
    <div class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4 dark:border-gray-800">
      <!-- Modern Hamburger Toggle BTN -->
      <button 
        :class="sidebarToggle ? 'bg-blue-600 ring-2 ring-blue-200 shadow-lg dark:bg-blue-500 dark:ring-blue-400/50' : 'bg-gray-800 hover:bg-blue-600 shadow-md dark:bg-gray-700 dark:hover:bg-blue-500'" 
        class="relative z-11111 flex h-10 w-10 items-center justify-center rounded-lg text-white transition-all duration-200 hover:scale-105 active:scale-95 lg:h-11 lg:w-11"
        @click.stop="sidebarToggle = !sidebarToggle" 
        :aria-pressed="sidebarToggle ? 'true' : 'false'" 
        aria-label="Toggle sidebar">
        
        <!-- SVG Hamburger Icon -->
        <svg class="w-5 h-5 transition-transform duration-200" :class="sidebarToggle ? 'rotate-90' : 'rotate-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" 
                :d="sidebarToggle ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'">
          </path>
        </svg>
      </button>

      <!-- Brand on mobile -->
      <a href="<?= base_url('#') ?>" class="lg:hidden inline-flex items-center gap-2 group">
        <div class="relative">
          <img src="<?= base_url('assets/images/logo/jasaku.png') ?>" alt="Jasa-Ku" class="h-8 w-8 rounded-md object-contain transition-transform duration-200 group-hover:scale-110" onerror="this.style.display='none'" />
          <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-purple-600/20 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
        </div>
        <span class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">Jasa-Ku</span>
      </a>

      <div class="hidden lg:block">
        <form>
          <!-- Optional search or actions -->
        </form>
      </div>
    </div>

    <div :class="menuToggle ? 'flex' : 'hidden'" class="shadow-theme-md w-full items-center justify-between gap-4 px-5 py-4 lg:flex lg:justify-end lg:px-0 lg:shadow-none">
      <div class="2xsm:gap-3 flex items-center gap-2">
        <!-- Enhanced Dark Mode Toggler -->
        <button @click.prevent="darkMode = !darkMode" class="group relative inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-700 hover:bg-gradient-to-br hover:from-yellow-400/10 hover:to-orange-500/10 dark:text-gray-400 dark:hover:from-blue-500/10 dark:hover:to-purple-600/10 transition-all duration-300 hover:scale-105">
          <!-- Sun Icon (Light Mode) -->
          <svg class="block dark:hidden w-5 h-5 transition-transform duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          
          <!-- Moon Icon (Dark Mode) -->
          <svg class="hidden dark:block w-5 h-5 transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>

        <span class="hidden sm:block text-sm text-gray-600 dark:text-gray-400 font-medium"><?= $this->session->userdata('nama') ?></span>
        
        <!-- Enhanced Logout Button -->
        <a href="<?= base_url('auth/logout') ?>" class="group relative overflow-hidden rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:text-white dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-white transition-all duration-300 hover:scale-105 hover:shadow-lg">
          <span class="absolute inset-0 w-0 bg-gradient-to-r from-red-500 to-pink-600 transition-all duration-300 ease-out group-hover:w-full"></span>
          <span class="relative flex items-center gap-2">
            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
          </span>
        </a>
      </div>
    </div>
  </div>
</header>