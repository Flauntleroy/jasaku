<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Riwayat Tanda Tangan</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Lihat status TTD per periode, filter berdasarkan tahun dan status.</p>
  </div>
  <nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li>
        <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('pegawai/dashboard') ?>">Dashboard</a>
      </li>
    </ol>
  </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
  <form method="get" class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
    <div>
      <label class="mb-1 block text-xs text-gray-500">Tahun</label>
      <select name="year" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:text-white">
        <option value="">Semua Tahun</option>
        <?php if (!empty($years)) foreach ($years as $y): ?>
          <option value="<?= (int)$y->year ?>" <?= (!empty($selected_year) && (int)$selected_year===(int)$y->year)?'selected':'' ?>><?= (int)$y->year ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="mb-1 block text-xs text-gray-500 dark:text-white">Status</label>
      <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:text-white">
        <option value="">Semua Status</option>
        <option value="unsigned" <?= ($selected_status==='unsigned')?'selected':'' ?>>Belum TTD</option>
        <option value="signed" <?= ($selected_status==='signed')?'selected':'' ?>>Sudah TTD</option>
      </select>
    </div>
    <div class="flex items-end">
      <button type="submit" class="rounded-lg bg-dark px-4 py-2 text-sm font-medium text-white" style="background-color:#1d2939">Terapkan</button>
    </div>
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead>
        <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
          <th class="px-4 py-3">Periode</th>
          <th class="px-4 py-3">Terima Setelah Pajak</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Tanggal TTD</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (empty($rows)): ?>
          <tr><td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">Tidak ada data.</td></tr>
        <?php else: foreach ($rows as $r): ?>
          <tr>
            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white"><?= html_escape(date('F Y', strtotime($r->periode))) ?></td>
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">Rp <?= number_format($r->terima_setelah_pajak, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-sm">
              <?php if (!empty($r->ttd_id)): ?>
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600 dark:bg-emerald-500/10 dark:text-white">Sudah TTD</span>
              <?php else: ?>
                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-600 dark:bg-amber-500/10 dark:text-white">Belum TTD</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-white"><?= !empty($r->signed_at) ? html_escape($r->signed_at) : '-' ?></td>
            <td class="px-4 py-3 text-sm">
              <?php if (!empty($r->ttd_id)): ?>
                <a href="<?= base_url('pegawai/review-tanda-tangan/'.$r->id) ?>" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">Lihat</a>
              <?php else: ?>
                <a href="<?= base_url('pegawai/dashboard') ?>" class="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-700">Tanda tangani</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
