<!-- Header & Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Data Jasa/Bonus</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola data jasa/bonus pegawai dan status tanda tangan.</p>
  </div>
  <div class="flex items-center gap-3">
    <nav aria-label="Breadcrumb" class="hidden sm:block">
      <ol class="flex items-center gap-2">
        <li>
          <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
        </li>
        <li class="text-gray-400">/</li>
        <li class="text-gray-700 dark:text-gray-300">Jasa/Bonus</li>
      </ol>
    </nav>
  </div>
</div>

<!-- Alerts -->
<?php if ($this->session->flashdata('success')): ?>
  <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700 dark:bg-green-500/10 dark:text-green-400"> <?= $this->session->flashdata('success') ?> </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-500/10 dark:text-red-400"> <?= $this->session->flashdata('error') ?> </div>
<?php endif; ?>

<div x-data="{ openCreateJasa: false, openEditJasa: false, openImportJasa: false, editJasa: {} }">
  <div class="mb-4 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <!-- Bulk Tambah -->
      <button @click="openCreateJasa = true" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium hover:opacity-90">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Tambah Data
      </button>
      <!-- Import Excel -->
      <button @click="openImportJasa = true" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
        Import Excel
      </button>
      <!-- Export Excel -->
      <a href="<?= base_url('admin/jasa-bonus?export=xlsx') ?>" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4M8 8h8M8 12h8M8 16h8"/></svg>
        Export Excel
      </a>
      <!-- Download Template -->
      <a href="<?= base_url('admin/jasa-bonus?template=xlsx') ?>" class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/></svg>
        Download Template
      </a>
    </div>
  </div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
  <div class="w-full overflow-x-auto">
    <table id="dataTables" class="min-w-full">
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
                <form method="post" onsubmit="return confirm('Kirim permintaan TTD via WhatsApp ke pegawai ini?')">
                  <input type="hidden" name="action" value="send_ttd_request" />
                  <input type="hidden" name="id" value="<?= $jb->id ?>" />
                  <button type="submit" class="rounded-lg bg-emerald-500/10 px-3 py-1 text-sm font-medium text-emerald-600 hover:bg-emerald-500/20">Minta TTD (WA)</button>
                </form>
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
  <div @click.outside="openCreateJasa = false" class=" max-w-3xl rounded-xl bg-white p-6 dark:bg-gray-900">
    <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white">Tambah Jasa/Bonus</h3>
    <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">Isi periode (bulan), nominal sebelum pajak, lalu pajak. Anda juga bisa centang “Kirim permintaan TTD via WhatsApp” agar pegawai langsung menerima notifikasi.</p>
    <form method="post" class="space-y-4" x-data="{ bruto: 0, p5: 0, p15: 0, p0: 0, netto: 0, hitung(){ this.netto = Math.max(0, (Number(this.bruto)||0) - (Number(this.p5)||0) - (Number(this.p15)||0) - (Number(this.p0)||0)); } }" @input.debounce.150ms="hitung()">
      <input type="hidden" name="action" value="create" />
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pegawai <span class="text-error-500">*</span></label>
          <select name="user_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required>
            <option value="">- Pilih Pegawai -</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u->id ?>"><?= $u->nama ?> (<?= $u->ruangan ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Periode (Bulan) <span class="text-error-500">*</span></label>
          <input name="periode_month" type="month" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
          <p class="mt-1 text-[11px] text-gray-500">Contoh: 2025-09 (otomatis disimpan sebagai tanggal 01 pada bulan tersebut).</p>
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Terima Sebelum Pajak (Bruto) <span class="text-error-500">*</span></label>
          <input name="terima_sebelum_pajak" x-model.number="bruto" placeholder="mis. 2500000" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
          <p class="mt-1 text-[11px] text-gray-500">Masukkan angka tanpa titik/koma.</p>
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 5%</label>
          <input name="pajak_5" x-model.number="p5" placeholder="mis. 125000" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 15%</label>
          <input name="pajak_15" x-model.number="p15" placeholder="mis. 0" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Pajak 0%</label>
          <input name="pajak_0" x-model.number="p0" placeholder="mis. 0" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Terima Setelah Pajak (Netto) <span class="text-error-500">*</span></label>
          <input name="terima_setelah_pajak" :value="netto" placeholder="otomatis dihitung" type="number" step="1" min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
          <p class="mt-1 text-[11px] text-gray-500">Netto = Bruto - (Pajak 5% + Pajak 15% + Pajak 0%). Bisa diedit manual bila perlu.</p>
        </div>
      </div>
      <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
          <input type="checkbox" name="send_wa_after_create" value="1" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" />
          Kirim permintaan TTD via WhatsApp setelah disimpan
        </label>
        <div class="flex items-center gap-2">
          <button type="button" @click="openCreateJasa = false" class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Batal</button>
          <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Import Modal -->
<div x-show="openImportJasa" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
  <div @click.outside="openImportJasa = false" class="max-w-lg rounded-xl bg-white p-6 dark:bg-gray-900">
    <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white">Import Jasa/Bonus dari Excel</h3>
    <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">Gunakan template Excel dengan header seperti contoh. Pilih periode (bulan) untuk seluruh baris pada file tersebut.</p>
    <form method="post" enctype="multipart/form-data" class="space-y-4">
      <input type="hidden" name="action" value="import_xlsx" />
      <div>
        <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Periode (Bulan) <span class="text-error-500">*</span></label>
        <input name="periode_month" type="month" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
      </div>
      <div>
        <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">File Excel (.xlsx) <span class="text-error-500">*</span></label>
        <input name="xlsx_file" type="file" accept=".xlsx,.xls" class="rounded-lg border border-dashed border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        <p class="mt-1 text-[11px] text-gray-500">Kolom: No, Nama, Ruangan, ASN, NIK, STATUS PTKP, Golongan, Terima Sebelum Pajak, Pajak 5%, Pajak 15%, Pajak 0%, Terima Setelah Pajak.</p>
      </div>
      <div class="mt-2 flex items-center justify-end gap-2">
        <button type="button" @click="openImportJasa = false" class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Batal</button>
        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Import</button>
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
