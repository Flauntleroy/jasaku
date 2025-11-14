<!-- Header & Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">
      Dashboard Admin
    </h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      Selamat datang, <?= $this->session->userdata('nama') ?>.
    </p>
  </div>
  <nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li>
        <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
      </li>
    </ol>
  </nav>
</div>

<!-- Dashboard Content -->
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12 space-y-6 xl:col-span-7">
    <!-- Metric Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
            <!-- Metric Item: Total Setelah Pajak -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9Z"/></svg>
              </div>
              <div class="mt-5 flex items-end justify-between">
                <div>
                  <span class="text-sm text-gray-500 dark:text-gray-400">Total Jasa</span>
                  <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    Rp <?= number_format((float)($stats['sum_netto'] ?? 0), 0, ',', '.') ?>
                  </h4>
                </div>
              </div>
            </div>

            <!-- Metric Item: Total Pajak -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 4h16v2H4zm0 4h12v2H4zm0 4h16v2H4zm0 4h12v2H4z"/></svg>
              </div>
              <div class="mt-5 flex items-end justify-between">
                <div>
                  <span class="text-sm text-gray-500 dark:text-gray-400">Total Pajak</span>
                  <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    Rp <?= number_format((float)($stats['sum_pajak'] ?? 0), 0, ',', '.') ?>
                  </h4>
                </div>
              </div>
            </div>

            <!-- Metric Item: Status TTD (Sudah/Belum) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>
              </div>
              <div class="mt-5">
                <span class="text-sm text-gray-500 dark:text-gray-400">Status TTD</span>
                <div class="mt-3 flex items-center justify-between gap-6">
                  <div class="flex items-baseline gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Sudah</span>
                    <span class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                      <?= isset($stats['total_signed']) ? number_format($stats['total_signed']) : '0' ?>
                    </span>
                  </div>
                  <div class="flex items-baseline gap-2 border-l border-gray-200 pl-6 dark:border-gray-700">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Belum</span>
                    <span class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                      <?= isset($stats['total_unsigned']) ? number_format($stats['total_unsigned']) : '0' ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Metric Item: Total Pegawai -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
              <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.761 0 5-2.686 5-6s-2.239-6-5-6-5 2.686-5 6 2.239 6 5 6Zm0 2c-4.418 0-8 2.239-8 5v3h16v-3c0-2.761-3.582-5-8-5Z"/></svg>
              </div>
              <div class="mt-5 flex items-end justify-between">
                <div>
                  <span class="text-sm text-gray-500 dark:text-gray-400">Total Pegawai</span>
                  <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    <?= isset($stats['total_pegawai']) ? number_format($stats['total_pegawai']) : '0' ?>
                  </h4>
                </div>
              </div>
            </div>
    </div>

    <!-- Chart: Sebelum Pajak vs Setelah Pajak per Bulan -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Total Jasa</h3>
      </div>
      <div class="custom-scrollbar max-w-full overflow-x-auto">
        <div class="-ml-5 min-w-[650px] pl-2 xl:min-w-full">
          <div id="chartMonthly" class="-ml-5 h-full min-w-[650px] pl-2 xl:min-w-full"></div>
        </div>
      </div>
    </div>

    

  </div>

  <div class="col-span-12 xl:col-span-5">
    <!-- Statistik: Belum TTD per Bulan -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6 mb-6">
      <div class="mb-4 flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Total Belum TTD per Bulan</h3>
          <!-- <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Pilih bulan periode saja</p> -->
        </div>
      </div>
      <form method="get" action="<?= base_url('admin/dashboard') ?>" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Bulan</label>
          <input type="month" name="unsigned_month" value="<?= html_escape($unsigned_month ?? date('Y-m')) ?>" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div class="flex items-end gap-2 sm:col-span-2">
          <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-gray-100 hover:bg-blue-700 dark:text-white/90">Apply</button>
          <a href="<?= base_url('admin/dashboard') ?>" class="rounded-lg bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">Reset</a>
        </div>
      </form>
      <div class="mt-5 rounded-xl bg-gray-50 p-4 dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between">
          <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Belum TTD</span>
            <h4 class="mt-1 text-3xl font-bold text-gray-800 dark:text-white/90">
              <?= number_format((int)($unsigned_count ?? 0)) ?>
            </h4>
          </div>
          <div class="text-right text-xs text-gray-500 dark:text-gray-400">
            <div>Bulan: <?= html_escape($unsigned_month_label ?? '') ?></div>
          </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-3">
          <div class="rounded-lg border border-gray-200 p-3 text-center dark:border-gray-800">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Dokumen</div>
            <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90"><?= number_format((int)($unsigned_month_total ?? 0)) ?></div>
          </div>
          <div class="rounded-lg border border-gray-200 p-3 text-center dark:border-gray-800">
            <div class="text-xs text-gray-500 dark:text-gray-400">Sudah TTD</div>
            <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90"><?= number_format((int)($unsigned_month_signed ?? 0)) ?></div>
          </div>
        </div>
      </div>
    </div>
    <!-- Chart: Persentase TTD -->
    <div class="rounded-2xl border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="shadow-default rounded-2xl bg-white px-5 pb-11 pt-5 dark:bg-gray-900 sm:px-6 sm:pt-6">
        <div class="flex justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Persentase TTD</h3>
            <!-- <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Dokumen bertanda tangan / total</p> -->
          </div>
        </div>
        <div class="relative max-h-[195px]"><div id="chartSigned" class="h-full"></div></div>
      </div>
    </div>
  </div>

  <div class="col-span-12">
    <!-- Chart: Trend Setelah Pajak -->
    <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
      <div class="mb-6 flex flex-col gap-5 sm:flex-row sm:justify-between">
        <div class="w-full">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistik Jasa Setelah Potongan Jasa</h3>
          <!-- <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Perkembangan penerimaan setelah pajak</p> -->
        </div>
      </div>
      <div class="custom-scrollbar max-w-full overflow-x-auto"><div id="chartNetto" class="-ml-4 min-w-[700px] pl-2"></div></div>
    </div>
  </div>

  <!-- Section map demografik dihapus karena tidak relevan -->

  <div class="col-span-12 xl:col-span-7">
    <!-- Recent Items Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
      <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div><h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Terbaru</h3></div>
      </div>
      <div class="w-full overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="border-y border-gray-100 dark:border-gray-800">
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Pegawai</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Periode</th>
              <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Terima Setelah Pajak</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Status TTD</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            <?php if (!empty($recent_jasa)): foreach ($recent_jasa as $row): ?>
              <tr>
                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($row->nama) ?></td>
                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($row->periode) ?></td>
                <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($row->terima_setelah_pajak, 0, ',', '.') ?></td>
                <td class="px-4 py-3 text-sm">
                  <?php if (!empty($row->ttd_id)): ?>
                    <span class="inline-flex items-center gap-1 rounded-full bg-success-50 px-2.5 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Signed</span>
                  <?php else: ?>
                    <span class="inline-flex items-center gap-1 rounded-full bg-warning-50 px-2.5 py-1 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">Pending</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada data.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')): ?>
<div class="fixed bottom-4 right-4 z-50 max-w-sm rounded-xl bg-gradient-to-r from-green-500 to-green-600 p-4 text-white shadow-2xl transform transition-all duration-300" 
     x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     x-init="setTimeout(() => show = false, 5000)">
  <div class="flex items-center gap-3">
    <div class="flex-shrink-0">
      <div class="rounded-full bg-white bg-opacity-20 p-1">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
      </div>
    </div>
    <div class="flex-1">
      <p class="font-medium">Berhasil!</p>
      <p class="text-sm opacity-90"><?= $this->session->flashdata('success') ?></p>
    </div>
    <button @click="show = false" class="flex-shrink-0 rounded-lg bg-white bg-opacity-20 p-1 hover:bg-opacity-30 transition-colors">
      <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
      </svg>
    </button>
  </div>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="fixed bottom-4 right-4 z-50 max-w-sm rounded-xl bg-gradient-to-r from-red-500 to-red-600 p-4 text-white shadow-2xl transform transition-all duration-300" 
     x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     x-init="setTimeout(() => show = false, 5000)">
  <div class="flex items-center gap-3">
    <div class="flex-shrink-0">
      <div class="rounded-full bg-white bg-opacity-20 p-1">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
      </div>
    </div>
    <div class="flex-1">
      <p class="font-medium">Error!</p>
      <p class="text-sm opacity-90"><?= $this->session->flashdata('error') ?></p>
    </div>
    <button @click="show = false" class="flex-shrink-0 rounded-lg bg-white bg-opacity-20 p-1 hover:bg-opacity-30 transition-colors">
      <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
      </svg>
    </button>
  </div>
</div>
<?php endif; ?>

<!-- Inline chart init for this page only -->
<script>
  (function initDashboardCharts() {
    const init = function() {
      
      const categories = <?= json_encode($monthly['categories'] ?? []) ?>;
      const bruto = <?= json_encode($monthly['bruto'] ?? []) ?>;
      const netto = <?= json_encode($monthly['netto'] ?? []) ?>;
      const signedPercent = <?= json_encode($signed_percent ?? 0) ?>;

      
      const elMonthly = document.querySelector('#chartMonthly');
      if (elMonthly) {
        const options = {
          series: [
            { name: 'Sebelum Pajak', data: bruto },
            { name: 'Setelah Pajak', data: netto },
          ],
          colors: ['#9CB9FF', '#465FFF'],
          chart: { type: 'bar', height: 300, toolbar: { show: false }, stacked: false, fontFamily: 'Outfit, sans-serif' },
          plotOptions: { bar: { columnWidth: '40%', borderRadius: 5, borderRadiusApplication: 'end' } },
          dataLabels: { enabled: false },
          stroke: { show: true, width: 4, colors: ['transparent'] },
          xaxis: { categories, axisBorder: { show: false }, axisTicks: { show: false } },
          yaxis: { labels: { formatter: val => new Intl.NumberFormat('id-ID').format(val) } },
          tooltip: { y: { formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) } },
          legend: { position: 'top', horizontalAlign: 'left' },
          grid: { yaxis: { lines: { show: true } } },
        };
        const chart = new ApexCharts(elMonthly, options); chart.render();
      }

      
      const elSigned = document.querySelector('#chartSigned');
      if (elSigned) {
        const options = {
          series: [Number(signedPercent)],
          colors: ['#465FFF'],
          chart: { type: 'radialBar', height: 260, sparkline: { enabled: true }, fontFamily: 'Outfit, sans-serif' },
          plotOptions: { radialBar: { startAngle: -90, endAngle: 90, hollow: { size: '70%' }, track: { background: '#E4E7EC', strokeWidth: '100%', margin: 5 }, dataLabels: { name: { show: false }, value: { fontSize: '28px', fontWeight: 600, offsetY: 8, formatter: val => val + '%' } } } },
          labels: ['Signed %'],
        };
        const chart = new ApexCharts(elSigned, options); chart.render();
      }

      
      const elNetto = document.querySelector('#chartNetto');
      if (elNetto) {
        const options = {
          series: [{ name: 'Setelah Pajak', data: netto }],
          colors: ['#465FFF'],
          chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
          dataLabels: { enabled: false },
          stroke: { curve: 'smooth', width: 3 },
          fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 100] } },
          xaxis: { categories, axisBorder: { show: false }, axisTicks: { show: false } },
          yaxis: { labels: { formatter: val => new Intl.NumberFormat('id-ID').format(val) } },
          tooltip: { y: { formatter: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val) } },
          grid: { yaxis: { lines: { show: true } } },
        };
        const chart = new ApexCharts(elNetto, options); chart.render();
      }
    };

    function ensureApexAndInit() {
      if (typeof ApexCharts !== 'undefined') {
        init();
      } else {
        
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/apexcharts@3.54.1';
        s.onload = init;
        document.head.appendChild(s);
      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', ensureApexAndInit);
    } else { ensureApexAndInit(); }
  })();
</script>