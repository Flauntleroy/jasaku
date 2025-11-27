<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="w-full">
  <div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Push Notifikasi</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">Kirim push notification ke aplikasi Android (topic: <span class="font-mono">all</span>).</p>
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
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Form Notifikasi</h3>
      <?php if (!empty($templates)): ?>
        <div class="mb-4">
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Pilih Template</label>
          <select id="template-select" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white">
            <option value="">— Pilih template —</option>
            <?php foreach ($templates as $tpl): ?>
              <option value="<?= htmlspecialchars($tpl['id']) ?>"><?= htmlspecialchars($tpl['title']) ?></option>
            <?php endforeach; ?>
          </select>
          <!-- <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Memilih template akan mengisi otomatis judul dan isi pesan.</p> -->
        </div>
      <?php endif; ?>
      <form method="post" action="<?= base_url('admin/notifikasi') ?>" class="space-y-4">
        <input type="hidden" name="action" value="send" />
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tipe Pesan</label>
          <select name="send_mode" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white">
            <option value="data" selected>Data payload (disarankan)</option>
            <option value="notification">Notification payload</option>
          </select>
          <!-- <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Data payload memberi kontrol penuh di client (suara custom saat foreground). Notification payload ditampilkan otomatis oleh FCM.</p> -->
        </div>
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Judul</label>
          <input type="text" name="title" placeholder="JasaKu - Notification" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" required />
        </div>
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Pesan</label>
          <textarea name="body" rows="4" placeholder="Halo kakak-kakak, sudah waktunya tanda tangan jasa ya 😊" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" required></textarea>
        </div>
        <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Channel ID (Android 8+)</label>
            <input type="text" name="channel_id" placeholder="Contoh: jasaku_alerts" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Harus sama dengan <code class="font-mono">NotificationChannel</code> di aplikasi.</p>
          </div>
          <div>
            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Sound (Android ≤ 7.1)</label>
            <input type="text" name="sound" placeholder="Nama file di res/raw (tanpa .mp3)" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Contoh: <span class="font-mono">notifikasi_ring</span> untuk <span class="font-mono">res/raw/notifikasi_ring.mp3</span>.</p>
          </div>
        </div> -->
        <div class="flex items-center gap-3">
          <button type="submit" class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-4 py-2">Kirim Notifikasi</button>
          <!-- <a href="https://firebase.google.com/docs/cloud-messaging/send-message" target="_blank" rel="noopener" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">Dokumentasi FCM</a> -->
        </div>
      </form>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black p-5">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Template Pesan</h3>
      <form method="post" action="<?= base_url('admin/notifikasi') ?>" class="space-y-3">
        <input type="hidden" name="action" value="add_template" />
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Judul Template</label>
          <input type="text" name="tpl_title" placeholder="Jasa Masuk" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" required />
        </div>
        <div>
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Isi Template</label>
          <textarea name="tpl_body" rows="4" placeholder="Halo kakak-kakak, sudah waktunya tanda tangan jasa ya 😊" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-black px-3 py-2 text-gray-800 dark:text-white" required></textarea>
        </div>
        <div class="flex items-center gap-3">
          <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2">Tambah Template</button>
        </div>
      </form>

      <!-- <hr class="my-6 border-gray-200 dark:border-gray-800" />
      <h4 class="text-base font-semibold text-gray-800 dark:text-white mb-3">Informasi</h4>
      <ul class="list-disc pl-5 text-gray-600 dark:text-gray-300 space-y-2">
        <li>Service account dibaca dari <code class="font-mono">api/service-account.json</code>.</li>
        <li>Pesan dikirim ke <code class="font-mono">topic: all</code>. Pastikan aplikasi Android subscribe ke topik ini.</li>
        <li>Template disimpan di <code class="font-mono">notification/templates.json</code>.</li>
        <li>Jika gagal, pesan error akan muncul di atas form.</li>
      </ul> -->
    </div>
  </div>
</section>

<script>
  (function(){
    var templates = <?php echo json_encode(isset($templates) ? $templates : []); ?>;
    var sel = document.getElementById('template-select');
    if (sel) {
      sel.addEventListener('change', function(){
        var id = this.value;
        var found = null;
        for (var i=0; i<templates.length; i++) { if (templates[i].id === id) { found = templates[i]; break; } }
        if (found) {
          var titleInput = document.querySelector('input[name="title"]');
          var bodyInput  = document.querySelector('textarea[name="body"]');
          if (titleInput) titleInput.value = found.title || '';
          if (bodyInput)  bodyInput.value  = found.body || '';
        }
      });
    }
  })();
</script>
