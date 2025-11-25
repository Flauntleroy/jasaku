<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Aktivasi Akun - Sistem Tanda Tangan Digital Jasa/Bonus</title>
  <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" />
  <link rel="manifest" href="<?= base_url('assets/manifest.webmanifest?v=1') ?>">
  <meta name="theme-color" content="#475569" />
  <link rel="apple-touch-icon" sizes="192x192" href="<?= base_url('assets/images/logo/jasaku-login.png') ?>">
  <link rel="apple-touch-icon" sizes="512x512" href="<?= base_url('assets/images/logo/jasaku-login.png') ?>">
    <?php
      $twCompiledPath = FCPATH . 'assets/css/tailwind.css';
      $isDev = defined('ENVIRONMENT') ? (ENVIRONMENT !== 'production') : true;
      $href = base_url('assets/css/tailwind.css' . (file_exists($twCompiledPath) ? ('?v=' . filemtime($twCompiledPath)) : ''));
    ?>
      <link rel="stylesheet" href="<?= $href ?>">
    <?php if ($isDev && !file_exists($twCompiledPath)) { ?>
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
    <?php } ?>
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
      page: 'activate', 
      loaded: true, 
      darkMode: false, 
      stickyMenu: false, 
      sidebarToggle: false, 
      scrollTop: false,
      showPassword: false,
      showPasswordConfirm: false
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
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500/10 rounded-2xl mb-6">
              <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
              Aktivasi Akun
            </h1>
            <p class="text-gray-500 dark:text-gray-400">
              Ikuti langkah-langkah untuk mengaktifkan akun Anda
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

          <!-- Form Container -->
          <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 shadow-lg animate-scale-in">
            <?php $mode = isset($mode) ? $mode : 'ask_phone'; ?>
            <?php if ($mode === 'ask_phone'): ?>
            <form class="space-y-6" action="<?= base_url('index.php/auth/activate') ?>" method="post">
              <input type="hidden" name="mode" value="ask_phone" />
              <!-- Phone Number -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                  Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <input
                    type="text"
                    name="phone"
                    placeholder="Contoh: 62812xxxx"
                    class="input-focus w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-300"
                    required
                  />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Format: 62 diikuti nomor tanpa tanda +</p>
              </div>

              <?php if (!empty($ask_nik)): ?>
              <!-- NIK Field -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                  NIK (untuk mengaitkan nomor)
                </label>
                <div class="relative group">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                  </div>
                  <input
                    type="text"
                    name="nik"
                    placeholder="Masukkan NIK"
                    class="input-focus w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-300"
                  />
                </div>
              </div>
              <?php endif; ?>

              <!-- Submit Button -->
              <button
                type="submit"
                class="btn-hover w-full py-4 px-6 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
              >
                <span class="flex items-center justify-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                  Kirim Kode Aktivasi
                </span>
              </button>
            </form>
            <?php elseif ($mode === 'verify_code'): ?>
            <form class="space-y-6" action="<?= base_url('auth/activate') ?>" method="post">
              <input type="hidden" name="mode" value="verify_code" />
              <!-- Phone Display -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                  Nomor WhatsApp
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <input
                    type="text"
                    name="phone"
                    value="<?= isset($phone) ? html_escape($phone) : '' ?>"
                    readonly
                    class="w-full pl-12 pr-4 py-4 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-600 dark:text-gray-300"
                  />
                </div>
              </div>

              <!-- Activation Code -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                  Kode Aktivasi <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                  </div>
                  <input
                    type="text"
                    name="activation_code"
                    placeholder="Masukkan 6 digit kode"
                    inputmode="numeric"
                    pattern="\d{6}"
                    maxlength="6"
                    class="input-focus w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-300"
                    required
                  />
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                class="btn-hover w-full py-4 px-6 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
              >
                <span class="flex items-center justify-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Verifikasi Kode
                </span>
              </button>
            </form>
            <?php elseif ($mode === 'set_password'): ?>
            <form class="space-y-6" action="<?= base_url('auth/activate') ?>" method="post">
              <input type="hidden" name="mode" value="set_password" />
              <input type="hidden" name="user_id" value="<?= isset($user_id) ? (int)$user_id : 0 ?>" />
              <!-- New Password -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                  Password Baru <span class="text-red-500">*</span>
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
                    placeholder="Minimal 6 karakter"
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

              <!-- Confirm Password -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block">
                  Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                  <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                  </div>
                  <input
                    :type="showPasswordConfirm ? 'text' : 'password'"
                    name="password_confirm"
                    placeholder="Ulangi password baru"
                    class="input-focus w-full pl-12 pr-12 py-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all duration-300"
                    required
                  />
                  <button
                    type="button"
                    @click="showPasswordConfirm = !showPasswordConfirm"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-brand-500 transition-colors"
                  >
                    <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                class="btn-hover w-full py-4 px-6 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
              >
                <span class="flex items-center justify-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  Simpan Password & Aktifkan
                </span>
              </button>
            </form>
            <?php endif; ?>

            <div class="text-center mt-2">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Sudah aktif? 
                <a href="<?= base_url('auth/login') ?>" class="text-brand-500 hover:text-brand-600 font-medium hover:underline transition-all">
                  Kembali ke Login
                </a>
              </p>
            </div>
          </div>
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
