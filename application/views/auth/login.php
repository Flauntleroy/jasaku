<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Login - Sistem Tanda Tangan Digital Jasa/Bonus</title>
  <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" />
  <link rel="manifest" href="<?= base_url('assets/manifest.webmanifest?v=1') ?>">
  <meta name="theme-color" content="#475569" />
  <link rel="apple-touch-icon" sizes="192x192" href="<?= base_url('assets/images/logo/jasaku-login.png') ?>">
  <link rel="apple-touch-icon" sizes="512x512" href="<?= base_url('assets/images/logo/jasaku-login.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand': {
                50: '#f8fafc',
                100: '#f1f5f9',
                300: '#cbd5e1',
                500: '#475569',
                600: '#334155',
                800: '#1e293b',
                950: '#0f172a'
              }
            },
            fontFamily: {
              'inter': ['Inter', 'system-ui', 'sans-serif']
            },
            animation: {
              'fade-in': 'fadeIn 0.6s ease-out',
              'slide-up': 'slideUp 0.8s ease-out',
              'pulse-subtle': 'pulseSubtle 2s infinite'
            }
          }
        }
      }
    </script>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
      
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      
      @keyframes slideUp {
        from { 
          opacity: 0; 
          transform: translateY(30px); 
        }
        to { 
          opacity: 1; 
          transform: translateY(0); 
        }
      }
      
      @keyframes pulseSubtle {
        0%, 100% { opacity: 0.8; }
        50% { opacity: 0.4; }
      }
      
      .glass-effect {
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
      }
      
      .input-focus {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      .input-focus:focus {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      }
      
      .btn-hover {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
      }
      
      .btn-hover:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.5s;
      }
      
      .btn-hover:hover:before {
        left: 100%;
      }
    </style>
  </head>
  <body 
    x-data="{ 
      page: 'comingSoon', 
      loaded: true, 
      darkMode: false, 
      stickyMenu: false, 
      sidebarToggle: false, 
      scrollTop: false,
      showPassword: false,
      username: '',
      password: ''
    }"
    x-init="
      darkMode = JSON.parse(localStorage.getItem('darkMode')) || false;
      $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
      setTimeout(() => loaded = false, 1000);
    "
    :class="{'dark': darkMode}"
    class="font-inter antialiased"
  >
    <!-- Preloader -->
    <div
      x-show="loaded"
      x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-gray-900"
    >
      <div class="relative">
        <div class="w-12 h-12 border-4 border-gray-200 rounded-full dark:border-gray-700"></div>
        <div class="absolute top-0 left-0 w-12 h-12 border-4 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
      </div>
    </div>

    <!-- Background Pattern -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-brand-500/5 to-transparent rounded-full blur-3xl animate-pulse-subtle"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-brand-500/5 to-transparent rounded-full blur-3xl animate-pulse-subtle" style="animation-delay: 1s;"></div>
    </div>

    <!-- Main Container -->
    <div class="min-h-screen flex">
      <!-- Left Panel - Form -->
      <div class="flex-1 flex items-center justify-center p-8 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="w-full max-w-md animate-slide-up">
          <!-- Header -->
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-33 h-33">
              <img src="<?= base_url('assets/images/logo/jasaku-login.png'); ?>" 
                  alt="Logo Jasa-Ku"
                  class="w-33 h-33 object-contain">
            </div>
            <!-- <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
              Jasa-Ku
            </h1> -->
            <p class="text-gray-500 dark:text-gray-400">
              <!-- Login untuk mengakses sistem tanda tangan digital jasa -->
            </p>
          </div>

          <!-- Server Messages -->
          <?php if (!empty($error) || $this->session->flashdata('error')): ?>
          <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span class="text-sm text-red-700 dark:text-red-300"><?= !empty($error) ? $error : $this->session->flashdata('error') ?></span>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($this->session->flashdata('success')): ?>
          <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-sm text-emerald-700 dark:text-emerald-300"><?= $this->session->flashdata('success') ?></span>
            </div>
          </div>
          <?php endif; ?>

          <!-- Login Form -->
          <form class="space-y-6" action="<?= base_url('auth/login') ?>" method="post">
            <!-- Username Field -->
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                Username
              </label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <input
                  type="text"
                  name="username"
                  x-model="username"
                  placeholder="Masukkan username Anda"
                  class="input-focus w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-300"
                  required
                />
              </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                Password
              </label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <input
                  :type="showPassword ? 'text' : 'password'"
                  name="password"
                  x-model="password"
                  placeholder="Masukkan password Anda"
                  class="input-focus w-full pl-12 pr-12 py-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-300"
                  required
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-brand-500 transition-colors"
                >
                  <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Login Button -->
            <button
              type="submit"
              class="btn-hover w-full py-4 px-6 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
            >
              <span class="flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Login
              </span>
            </button>

            <!-- Additional Link -->
            <div class="text-center">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Belum punya akun? 
                <a href="<?= base_url('auth/activate') ?>" class="text-brand-500 hover:text-brand-600 font-medium hover:underline transition-all">
                  Aktivasi Akun
                </a>
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Lupa password? 
                <a href="<?= base_url('auth/forgot_password') ?>" class="text-brand-500 hover:text-brand-600 font-medium hover:underline transition-all">
                  Reset Password
                </a>
              </p>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Panel - Branding -->
      <div class="hidden lg:flex flex-1 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 items-center justify-center relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
          <div class="absolute top-0 right-0 w-96 h-96 bg-brand-500/10 rounded-full -translate-y-48 translate-x-48"></div>
          <div class="absolute bottom-0 left-0 w-96 h-96 bg-brand-500/10 rounded-full translate-y-48 -translate-x-48"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center max-w-md animate-fade-in">
          <!-- <div class="inline-flex items-center justify-center w-24 h-24 bg-white dark:bg-gray-800 rounded-3xl shadow-xl mb-8">
            <svg class="w-12 h-12 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div> -->
          
          <h2 class="text-xl text-gray-600 dark:text-gray-400 mb-8">
            Sistem Tanda Tangan Jasa Digital
          </h2>
          
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
            RSUD H. ABDUL AZIZ MARABAHAN
          </h1>

          
          
          <!-- <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
            Jasa & Bonus
          </p>
          
          <div class="space-y-4 text-gray-500 dark:text-gray-400">
            <div class="flex items-center justify-center">
              <svg class="w-5 h-5 text-brand-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>Aman & Terpercaya</span>
            </div>
            <div class="flex items-center justify-center">
              <svg class="w-5 h-5 text-brand-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>Mudah Digunakan</span>
            </div>
            <div class="flex items-center justify-center">
              <svg class="w-5 h-5 text-brand-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span>Hasil Profesional</span>
            </div> -->
          </div>
        </div>
      </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button
      @click="darkMode = !darkMode"
      class="fixed bottom-8 right-8 w-14 h-14 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group"
    >
      <svg x-show="!darkMode" class="w-6 h-6 text-gray-600 group-hover:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
      </svg>
      <svg x-show="darkMode" class="w-6 h-6 text-gray-400 group-hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
      </svg>
    </button>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  </body>
</html>