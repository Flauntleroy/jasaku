<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Dashboard Pegawai</h2>
  <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ringkasan perolehan jasa dan riwayat terbaru.</p>
  </div>
  <nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li>
        <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('pegawai/dashboard') ?>">Dashboard</a>
      </li>
    </ol>
  </nav>
  </div>

<?php if (empty($current_jasa)): ?>
  <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center dark:border-gray-800 dark:bg-white/[0.03]">
    <p class="text-gray-600 dark:text-gray-300">Belum ada data jasa untuk Anda.</p>
  </div>
<?php else: ?>

  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Notifikasi Utama -->
    <?php if (!empty($unsigned_list)): ?>
      <div class="col-span-12">
        <div class="rounded-2xl border border-amber-300 bg-amber-50 p-5 text-amber-900 shadow-sm dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-300">
          <div class="flex items-start justify-between gap-3">
            <div>
              <h3 class="text-lg font-semibold">Ada dokumen yang perlu ditandatangani</h3>
              <p class="mt-1 text-sm">Anda memiliki <?= count($unsigned_list) ?> dokumen belum TTD pada tahun <?= date('Y') ?>.</p>
            </div>
            <a href="<?= base_url('pegawai/tanda-tangan') ?>" class="rounded-lg border border-emerald-300 bg-white px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/40 dark:bg-transparent dark:text-emerald-300">BUKA</a>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="col-span-12">
        <div class="rounded-2xl border border-emerald-300 bg-emerald-50 p-5 text-emerald-800 shadow-sm dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-300">
          <div class="flex items-start justify-between gap-3">
            <div>
              <h3 class="text-lg font-semibold">Tidak ada dokumen yang menunggu tanda tangan</h3>
              <p class="mt-1 text-sm">Semua dokumen tahun ini sudah ditandatangani. Terima kasih!</p>
            </div>
            <a href="<?= base_url('pegawai/history') ?>" class="rounded-lg border border-emerald-300 bg-white px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/40 dark:bg-transparent dark:text-emerald-300">Lihat Riwayat</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- KPI Kartu -->
    <div class="col-span-12 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Jasa (YTD)</p>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">Rp <?= number_format($ytd_netto ?? 0, 0, ',', '.') ?></h4>
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Potongan Pajak (YTD)</p>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">Rp <?= number_format($ytd_pajak ?? 0, 0, ',', '.') ?></h4>
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Jasa Diterima (YTD)</p>
        <h4 class="mt-2 text-title-sm font-bold text-emerald-600 dark:text-emerald-400">Rp <?= number_format($ytd_avg ?? 0, 0, ',', '.') ?></h4>
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Persentase TTD (YTD)</p>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90"><?= number_format($ytd_signed_percent ?? 0, 0) ?>%</h4>
      </div>
    </div>
    
    <div class="col-span-12 lg:col-span-6">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Jasa Terbaru</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Periode: <?= html_escape(date('F Y', strtotime($current_jasa->periode))) ?></p>
          </div>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-4">
          <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Terima Sebelum Pajak</p>
            <p class="mt-1 text-xl font-semibold text-gray-800 dark:text-white">Rp <?= number_format($current_jasa->terima_sebelum_pajak, 0, ',', '.') ?></p>
          </div>
          <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Terima Setelah Pajak</p>
            <p class="mt-1 text-xl font-semibold text-emerald-600 dark:text-emerald-400">Rp <?= number_format($current_jasa->terima_setelah_pajak, 0, ',', '.') ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-span-12 lg:col-span-6">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Perolehan Jasa Tahun Ini</h3>
        </div>
        <div id="pegawaiLineChart" class="h-64 w-full"></div>
      </div>
    </div>

    <!-- Riwayat ringkas selalu tampil -->
    <div class="col-span-12">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-3 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Riwayat Terbaru</h3>
          <a href="<?= base_url('pegawai/history') ?>" class="text-sm font-medium" style="color:#2563eb">Lihat semua</a>
        </div>
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-white/[0.06]">
              <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                <th class="px-4 py-2">Periode</th>
                <th class="px-4 py-2">Nilai Bersih</th>
                <th class="px-4 py-2">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
              <?php if (!empty($history_preview)): foreach ($history_preview as $h): ?>
                <tr>
                  <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200"><?= html_escape(date('F Y', strtotime($h->periode))) ?></td>
                  <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-white">Rp <?= number_format($h->terima_setelah_pajak, 0, ',', '.') ?></td>
                  <td class="px-4 py-2 text-sm">
                    <?php if (!empty($h->ttd_id)): ?>
                      <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600">Sudah TTD</span>
                    <?php else: ?>
                      <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-600">Belum TTD</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr><td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada data.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Flash Messages -->
  <?php if ($this->session->flashdata('success')): ?>
    <div class="mt-6 rounded-xl bg-emerald-50 p-4 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"> <?= $this->session->flashdata('success') ?> </div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="mt-6 rounded-xl bg-red-50 p-4 text-red-700 dark:bg-red-500/10 dark:text-red-400"> <?= $this->session->flashdata('error') ?> </div>
  <?php endif; ?>

<?php endif; ?>

<script>
  (function(){
    if (!window.ApexCharts) return;
    const el = document.querySelector('#pegawaiLineChart');
    if (!el) return;
    const categories = <?= json_encode($line_categories ?? []) ?>;
    const dataNetto = <?= json_encode($line_netto ?? []) ?>;
    const options = {
      chart: { type: 'line', height: 280, toolbar: { show: false }, animations: { enabled: true } },
      stroke: { width: 3, curve: 'smooth' },
      colors: ['#10b981'],
      dataLabels: { enabled: false },
      xaxis: { categories: categories },
      yaxis: { labels: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v)) } },
      tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v)) } },
      series: [{ name: 'Netto', data: dataNetto }],
      grid: { borderColor: 'rgba(107,114,128,0.2)' }
    };
    const chart = new ApexCharts(el, options);
    chart.render();
  })();
</script>

