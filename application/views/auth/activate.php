<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aktivasi Akun - Sistem Tanda Tangan Digital</title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" />
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" />
  </head>
  <body class="bg-white dark:bg-gray-900">
    <div class="relative p-6 sm:p-0 min-h-screen flex items-center justify-center">
      <div class="w-full max-w-md">
        <div class="mb-6 text-center">
          <h1 class="mb-2 text-title-md font-semibold text-gray-800 dark:text-white">Aktivasi Akun</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan NIK, kode aktivasi, dan setel password baru.</p>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
          <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700 dark:bg-green-500/10 dark:text-green-400"> <?= $this->session->flashdata('success') ?> </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
          <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-500/10 dark:text-red-400"> <?= $error ?> </div>
        <?php elseif ($this->session->flashdata('error')): ?>
          <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-500/10 dark:text-red-400"> <?= $this->session->flashdata('error') ?> </div>
        <?php endif; ?>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <?php $mode = isset($mode) ? $mode : 'ask_phone'; ?>
          <?php if ($mode === 'ask_phone'): ?>
            <form action="<?= base_url('index.php/auth/activate') ?>" method="post" class="space-y-4">
              <input type="hidden" name="mode" value="ask_phone" />
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor WhatsApp<span class="text-error-500">*</span></label>
                <input type="text" name="phone" required placeholder="Contoh: 62812xxxx" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
              </div>
              <?php if (!empty($ask_nik)): ?>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NIK (untuk mengaitkan nomor)</label>
                <input type="text" name="nik" placeholder="Masukkan NIK" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
              </div>
              <?php endif; ?>
              <div>
                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Kirim Kode</button>
              </div>
            </form>
          <?php elseif ($mode === 'verify_code'): ?>
            <form action="<?= base_url('index.php/auth/activate') ?>" method="post" class="space-y-4">
              <input type="hidden" name="mode" value="verify_code" />
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor WhatsApp</label>
                <input type="text" name="phone" value="<?= isset($phone) ? html_escape($phone) : '' ?>" readonly class="h-11 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white/90" />
              </div>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kode Aktivasi<span class="text-error-500">*</span></label>
                <input type="text" name="activation_code" required placeholder="Masukkan kode aktivasi" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
              </div>
              <div>
                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Verifikasi</button>
              </div>
            </form>
          <?php elseif ($mode === 'set_password'): ?>
            <form action="<?= base_url('index.php/auth/activate') ?>" method="post" class="space-y-4">
              <input type="hidden" name="mode" value="set_password" />
              <input type="hidden" name="user_id" value="<?= isset($user_id) ? (int)$user_id : 0 ?>" />
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru<span class="text-error-500">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
              </div>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password<span class="text-error-500">*</span></label>
                <input type="password" name="password_confirm" required placeholder="Ulangi password baru" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
              </div>
              <div>
                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-brand-600">Simpan Password</button>
              </div>
            </form>
          <?php endif; ?>
          <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            Sudah aktif? <a class="text-brand-600 hover:underline" href="<?= base_url('auth/login') ?>">Kembali ke Login</a>
          </div>
        </div>
      </div>
    </div>

    <script defer src="<?= base_url('assets/js/bundle.js') ?>"></script>
  </body>
</html>
