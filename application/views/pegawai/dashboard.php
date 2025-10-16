<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Dashboard Pegawai</h2>
  <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ringkasan perolehan jasa dan riwayat terbaru.</p> -->
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
            <a href="<?= base_url('pegawai/tanda-tangan') ?>" class="rounded-lg border border-emerald-300 bg-white px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/40 dark:bg-transparent dark:text-white">BUKA</a>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="col-span-12">
        <div class="rounded-2xl border border-emerald-300 bg-emerald-50 p-5 text-emerald-800 shadow-sm dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-white">
          <div class="flex items-start justify-between gap-3">
            <div>
              <h3 class="text-lg font-semibold dark:text-white">Tidak ada dokumen yang menunggu tanda tangan</h3>
              <p class="mt-1 text-sm dark:text-white">Semua dokumen tahun ini sudah ditandatangani. Terima kasih!</p>
            </div>
            <a href="<?= base_url('pegawai/history') ?>" class="rounded-lg border border-emerald-300 bg-white px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/40 dark:bg-transparent dark:text-white">Lihat Riwayat</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- KPI Kartu -->
    <div class="col-span-12 flex justify-between items-center mb-2">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Ringkasan Jasa <?= date('Y') ?></h3>
      <button id="toggleBalance" class="flex items-center gap-2 rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">
        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        </svg>
        <span id="toggleText">Sembunyikan Saldo</span>
      </button>
    </div>
    <div class="col-span-12 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Jasa <?= date('Y') ?></p>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90 balance-value hidden">Rp <?= number_format($ytd_netto ?? 0, 0, ',', '.') ?></h4>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90 balance-hidden">Rp -------</</h4>
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Potongan Pajak  <?= date('Y') ?></p>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90 balance-value hidden">Rp <?= number_format($ytd_pajak ?? 0, 0, ',', '.') ?></h4>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90 balance-hidden">Rp -------</</h4>
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Jasa Diterima  <?= date('Y') ?></p>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90 balance-value hidden">Rp <?= number_format($ytd_avg ?? 0, 0, ',', '.') ?></h4>
        <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90 balance-hidden">Rp -------</</h4>
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Persentase TTD  <?= date('Y') ?></p>
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
            <p class="mt-1 text-xl font-semibold text-gray-800 dark:text-white balance-value hidden">Rp <?= number_format($current_jasa->terima_sebelum_pajak, 0, ',', '.') ?></p>
            <p class="mt-1 text-xl font-semibold text-gray-800 dark:text-white balance-hidden">Rp -------</p>
          </div>
          <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Terima Setelah Pajak</p>
            <p class="mt-1 text-xl font-semibold text-emerald-600 dark:text-white balance-value hidden">Rp <?= number_format($current_jasa->terima_setelah_pajak, 0, ',', '.') ?></p>
            <p class="mt-1 text-xl font-semibold text-emerald-600 dark:text-white balance-hidden">Rp -------</</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-span-12 lg:col-span-6">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= html_escape($line_title ?? 'Perolehan Jasa') ?></h3>
        </div>
        <div id="pegawaiLineChart" class="h-64 w-full"></div>
      </div>
    </div>

    <!-- Riwayat ringkas selalu tampil -->
    <div class="col-span-12">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-3 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Riwayat Terbaru</h3>
          <a href="<?= base_url('pegawai/history') ?>" class="text-sm font-medium" style="color:#02317e">Lihat semua</a>
        </div>
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-white/[0.06]">
              <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:bg-white/[0.03]">
                <th class="px-4 py-2">Periode</th>
                <th class="px-4 py-2">Nilai Bersih</th>
                <th class="px-4 py-2">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
              <?php if (!empty($history_preview)): foreach ($history_preview as $h): ?>
                <tr>
                  <td class="px-4 py-2 text-sm text-gray-800 dark:text-white"><?= html_escape(date('F Y', strtotime($h->periode))) ?></td>
                  <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-white">
                    <span class="balance-value">Rp <?= number_format($h->terima_setelah_pajak, 0, ',', '.') ?></span>
                    <span class="balance-hidden hidden">Rp ••••••••</span>
                  </td>
                  <td class="px-4 py-2 text-sm">
                    <?php if (!empty($h->ttd_id)): ?>
                      <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600 dark:text-white">Sudah TTD</span>
                    <?php else: ?>
                      <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-600 dark:text-white">Belum TTD</span>
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
    const el = document.querySelector('#pegawaiLineChart');
    if (!el) return;

    function renderPegawaiChart() {
      const categories = <?= json_encode($line_categories ?? []) ?>;
      const dataNettoRaw = <?= json_encode($line_netto ?? []) ?>;

      const cats = Array.isArray(categories) ? categories : [];
      let dataNetto = Array.isArray(dataNettoRaw) ? dataNettoRaw.map(v => Number(v) || 0) : [];
      if (dataNetto.length < cats.length) {
        dataNetto = dataNetto.concat(new Array(cats.length - dataNetto.length).fill(0));
      } else if (dataNetto.length > cats.length) {
        dataNetto = dataNetto.slice(0, cats.length);
      }

      const options = {
        chart: { type: 'bar', height: 280, toolbar: { show: false }, animations: { enabled: true } },
        plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 4 } },
        colors: ['#1d2939'],
        dataLabels: { enabled: false },
        xaxis: { categories: cats },
        yaxis: { labels: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v)) } },
        tooltip: { y: { formatter: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(v)) } },
        series: [{ name: 'Bersih', data: dataNetto }],
        grid: { borderColor: 'rgb(48,49,53)' },
        noData: { text: 'Tidak ada data', align: 'center', style: { color: '#303135' } }
      };
      const chart = new ApexCharts(el, options);
      chart.render();
    }

    if (typeof ApexCharts !== 'undefined') {
      renderPegawaiChart();
    } else {
      var s = document.createElement('script');
      s.src = 'https://cdn.jsdelivr.net/npm/apexcharts@3.54.1';
      s.onload = renderPegawaiChart;
      document.head.appendChild(s);
    }
  })();
</script>

