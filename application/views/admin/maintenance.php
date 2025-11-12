<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="w-full">
  <div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Mode Pemeliharaan</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">Aktifkan atau nonaktifkan halaman maintenance, dan atur durasi hitung mundur.</p>
    <?php if ($this->session->flashdata('success')): ?>
      <div class="mt-3 rounded-lg border border-green-600 text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 px-4 py-3">
        <?= $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
      <div class="mt-3 rounded-lg border border-red-600 text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20 px-4 py-3">
        <?= $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black p-5">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Status</h3>
      <?php if (!empty($is_active)): ?>
        <p class="text-gray-600 dark:text-gray-300">Maintenance saat ini <span class="font-semibold text-orange-600">AKTIF</span>.</p>
        <?php 
          $reason = isset($maintenance['reason']) ? $maintenance['reason'] : null; 
          $endAt = isset($maintenance['end_at']) ? (int)$maintenance['end_at'] : null; 
        ?>
        <?php if ($reason): ?>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Alasan: <?= html_escape($reason) ?></p>
        <?php endif; ?>
        <?php if (!empty($remaining_secs) && $remaining_secs > 0): ?>
          <div class="mt-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Berakhir pada: <?= date('d M Y H:i', $endAt) ?></div>
            <div class="mt-2 inline-flex items-center gap-2 rounded-full border border-orange-500 text-orange-600 px-3 py-1">
              <span class="text-xs font-semibold">COUNTDOWN</span>
              <span id="admin-maint-countdown" class="text-sm font-mono"></span>
            </div>
          </div>
          <script>
            (function(){
              var secs = <?= (int)$remaining_secs ?>;
              function fmt(s){
                var h = Math.floor(s/3600); var m = Math.floor((s%3600)/60); var d = s%60;
                function pad(n){ return (n<10?'0':'')+n; }
                return pad(h)+':'+pad(m)+':'+pad(d);
              }
              function tick(){
                var el = document.getElementById('admin-maint-countdown');
                if (!el) return;
                el.textContent = fmt(secs);
                secs--; if (secs < 0) secs = 0;
              }
              tick(); setInterval(tick, 1000);
            })();
          </script>
        <?php else: ?>
          <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Countdown tidak tersedia.</p>
        <?php endif; ?>
        <form method="post" action="<?= base_url('admin/maintenance') ?>" class="mt-5">
          <input type="hidden" name="action" value="disable" />
          <button type="submit" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-4 py-2">Nonaktifkan Maintenance</button>
        </form>
      <?php else: ?>
        <p class="text-gray-600 dark:text-gray-300">Maintenance saat ini <span class="font-semibold text-green-600">NONAKTIF</span>.</p>
      <?php endif; ?>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black p-5">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Aktifkan Maintenance</h3>
      <form method="post" action="<?= base_url('admin/maintenance') ?>" class="space-y-4">
        <input type="hidden" name="action" value="enable" />
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Durasi (menit)</label>
          <input type="number" min="1" name="duration_minutes" value="30" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" />
        </div>
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Alasan (opsional)</label>
          <textarea name="reason" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" placeholder="Contoh: Update fitur, migrasi data, dsb."></textarea>
        </div>
        <div>
          <button type="submit" class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-4 py-2">Aktifkan Maintenance</button>
        </div>
      </form>
    </div>
  </div>
</section>