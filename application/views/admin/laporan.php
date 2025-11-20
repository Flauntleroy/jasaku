<!-- Header & Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Laporan Tanda Tangan</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Filter berdasarkan periode dan ekspor data.</p>
  </div>
  <nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li>
        <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
      </li>
      <li class="text-gray-400">/</li>
      <li class="text-gray-700 dark:text-gray-300">Laporan</li>
    </ol>
  </nav>
</div>

<!-- Filters -->
<div class="mb-4 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
  <form method="get" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
    <div>
      <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Dari Periode</label>
      <input type="date" name="start_date" value="<?= html_escape($start_date) ?>" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
    </div>
    <div>
      <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Sampai Periode</label>
      <input type="date" name="end_date" value="<?= html_escape($end_date) ?>" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
    </div>
    <div class="flex items-end gap-2">
      <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Terapkan</button>
      <a href="<?= current_url() ?>?export=xlsx<?= $start_date? '&start_date='.urlencode($start_date):'' ?><?= $end_date? '&end_date='.urlencode($end_date):'' ?>" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Download XLSX (+ Foto TTD)</a>
      <a href="<?= current_url() ?>?export=zip<?= $start_date? '&start_date='.urlencode($start_date):'' ?><?= $end_date? '&end_date='.urlencode($end_date):'' ?>" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700">Download ZIP (CSV + Foto TTD)</a>
      <a href="<?= current_url() ?>?print=1<?= $start_date? '&start_date='.urlencode($start_date):'' ?><?= $end_date? '&end_date='.urlencode($end_date):'' ?>" target="_blank" class="rounded-lg bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">Print</a>
    </div>
  </form>
</div>

<!-- Table -->
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
  <div class="w-full overflow-x-auto">
    <table id="jasaTable" class="min-w-full">
      <thead>
        <tr class="border-y border-gray-100 dark:border-gray-800">
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal TTD</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Nama</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Ruangan</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Periode</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Sblm Pajak</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Pajak 5%</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Pajak 15%</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Pajak 0%</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Stlh Pajak</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        <?php if (!empty($laporan)): foreach ($laporan as $row): ?>
          <tr>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($row->signed_at) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($row->nama) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($row->ruangan) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($row->periode) ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($row->terima_sebelum_pajak, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($row->pajak_5, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($row->pajak_15, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($row->pajak_0, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($row->terima_setelah_pajak, 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">Tidak ada data.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
