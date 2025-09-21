<!-- Header & Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Data Jasa/Bonus</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola data jasa/bonus pegawai dan status tanda tangan.</p>
  </div>
  <nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li>
        <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
      </li>
      <li class="text-gray-400">/</li>
      <li class="text-gray-700 dark:text-gray-300">Jasa/Bonus</li>
    </ol>
  </nav>
</div>

<!-- Alerts -->
<?php if ($this->session->flashdata('success')): ?>
  <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700 dark:bg-green-500/10 dark:text-green-400"> <?= $this->session->flashdata('success') ?> </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-500/10 dark:text-red-400"> <?= $this->session->flashdata('error') ?> </div>
<?php endif; ?>

<div x-data="{ openCreateJasa: false, openEditJasa: false, editJasa: {} }">
  <div class="mb-4 flex items-center justify-between">
    <div></div>
    <button @click="openCreateJasa = true" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
    Tambah Data
    </button>
  </div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
  <div class="w-full overflow-x-auto">
    <table class="min-w-full">
      <thead>
        <tr class="border-y border-gray-100 dark:border-gray-800">
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Pegawai</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Periode</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Sebelum Pajak</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Pajak 5%</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Pajak 15%</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Pajak 0%</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Setelah Pajak</th>
          <th class="px-4 py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Status TTD</th>
          <th class="px-4 py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        <?php if (!empty($jasa_bonus)): foreach ($jasa_bonus as $jb): ?>
          <tr>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($jb->nama) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($jb->periode) ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($jb->terima_sebelum_pajak, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($jb->pajak_5, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($jb->pajak_15, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($jb->pajak_0, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Rp <?= number_format($jb->terima_setelah_pajak, 0, ',', '.') ?></td>
            <td class="px-4 py-3 text-center text-sm">
              <?php if (!empty($jb->ttd_id)): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-success-50 px-2.5 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Sudah</span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-warning-50 px-2.5 py-1 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">Belum</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-center text-sm">
              <div class="inline-flex items-center gap-2">
                <button @click='editJasa = <?= json_encode($jb) ?>; openEditJasa = true' class="rounded-lg bg-amber-500/10 px-3 py-1 text-sm font-medium text-amber-600 hover:bg-amber-500/20">Edit</button>
                <form method="post" onsubmit="return confirm('Hapus data ini?')">
                  <input type="hidden" name="action" value="delete" />
                  <input type="hidden" name="id" value="<?= $jb->id ?>" />
                  <button type="submit" class="rounded-lg bg-red-500/10 px-3 py-1 text-sm font-medium text-red-600 hover:bg-red-500/20">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada data.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Create Modal -->
<div x-show="openCreateJasa" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
  <div @click.outside="openCreateJasa = false" class="w-full max-w-2xl rounded-xl bg-white p-6 dark:bg-gray-900">
    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Tambah Jasa/Bonus</h3>
    <form method="post" class="space-y-4">
      <input type="hidden" name="action" value="create" />
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pegawai</label>
          <select name="user_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required>
            <option value="">- Pilih Pegawai -</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u->id ?>"><?= $u->nama ?> (<?= $u->ruangan ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Periode</label>
          <input name="periode" type="date" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Terima Sebelum Pajak</label>
          <input name="terima_sebelum_pajak" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 5%</label>
          <input name="pajak_5" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 15%</label>
          <input name="pajak_15" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 0%</label>
          <input name="pajak_0" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Terima Setelah Pajak</label>
          <input name="terima_setelah_pajak" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-end gap-2">
        <button type="button" @click="openCreateJasa = false" class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Batal</button>
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div x-show="openEditJasa" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
  <div @click.outside="openEditJasa = false" class="w-full max-w-2xl rounded-xl bg-white p-6 dark:bg-gray-900">
    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Edit Jasa/Bonus</h3>
    <form method="post" class="space-y-4">
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="id" :value="editJasa.id" />
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pegawai</label>
          <input class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" :value="`${editJasa.nama} (${editJasa.ruangan})`" disabled />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Periode</label>
          <input name="periode" type="date" :value="editJasa.periode" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" disabled />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Terima Sebelum Pajak</label>
          <input name="terima_sebelum_pajak" :value="editJasa.terima_sebelum_pajak" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 5%</label>
          <input name="pajak_5" :value="editJasa.pajak_5" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 15%</label>
          <input name="pajak_15" :value="editJasa.pajak_15" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 0%</label>
          <input name="pajak_0" :value="editJasa.pajak_0" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Terima Setelah Pajak</label>
          <input name="terima_setelah_pajak" :value="editJasa.terima_setelah_pajak" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-end gap-2">
        <button type="button" @click="openEditJasa = false" class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Batal</button>
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

</div>
