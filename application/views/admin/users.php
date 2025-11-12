<!-- Header & Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Kelola Pegawai</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tambah, ubah, dan hapus data pegawai.</p>
  </div>
  <nav aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
      <li>
        <a class="font-medium text-gray-500 transition-colors hover:text-primary" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
      </li>
      <li class="text-gray-400">/</li>
      <li class="text-gray-700 dark:text-gray-300">Pegawai</li>
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

<!-- Actions + Content Wrapper with Alpine State -->
<div x-data="{ openCreateUser: false, openEditUser: false, editUser: {} }">
  <div class="mb-4 flex items-center justify-between">
    <div></div>
    <button @click="openCreateUser = true" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
    Tambah Pegawai
    </button>
  </div>

<!-- Table -->
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
  <div class="w-full overflow-x-auto">
    <table id="jasaTable" class="min-w-full">
      <thead>
        <tr class="border-y border-gray-100 dark:border-gray-800">
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Nama</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Username</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">HP</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Ruangan</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Role</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
          <th class="px-4 py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        <?php if (!empty($users)): foreach ($users as $u): ?>
          <tr>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($u->nama) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($u->username) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($u->phone ?? '') ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= html_escape($u->ruangan) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 uppercase"><?= html_escape($u->role) ?></td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
              <?php if ($u->role === 'admin'): ?>
                <span class="rounded bg-green-100 px-2 py-0.5 text-xs text-green-700">Admin</span>
              <?php elseif ((int)($u->is_active ?? 0) === 1): ?>
                <span class="rounded bg-green-100 px-2 py-0.5 text-xs text-green-700">Aktif</span>
              <?php else: ?>
                <span class="rounded bg-yellow-100 px-2 py-0.5 text-xs text-yellow-700">Belum Aktif</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-center">
              <div class="inline-flex items-center gap-2">
                <?php 
                  $safeUser = [
                    'id' => $u->id,
                    'nama' => $u->nama,
                    'username' => $u->username,
                    'role' => $u->role,
                    'ruangan' => $u->ruangan,
                    'asn' => $u->asn,
                    'nik' => $u->nik,
                    'status_ptkp' => $u->status_ptkp,
                    'golongan' => $u->golongan,
                    'phone' => $u->phone ?? '',
                  ];
                ?>
                <button @click='editUser = <?= json_encode($safeUser) ?>; openEditUser = true' class="rounded-lg bg-amber-500/10 px-3 py-1 text-sm font-medium text-amber-600 hover:bg-amber-500/20">Edit</button>
                <?php if ($u->role !== 'admin'): ?>
                  <form method="post" class="inline" onsubmit="return confirm('Kirim kode aktivasi via WhatsApp?')">
                    <input type="hidden" name="action" value="send_activation_whatsapp" />
                    <input type="hidden" name="id" value="<?= $u->id ?>" />
                    <button type="submit" class="rounded-lg bg-emerald-500/10 px-3 py-1 text-sm font-medium text-emerald-600 hover:bg-emerald-500/20">Kirim WA</button>
                  </form>
                  <form method="post" class="inline" onsubmit="return confirm('Kirim kode aktivasi baru untuk user ini?')">
                    <input type="hidden" name="action" value="generate_activation" />
                    <input type="hidden" name="id" value="<?= $u->id ?>" />
                    <button type="submit" class="rounded-lg bg-indigo-500/10 px-3 py-1 text-sm font-medium text-indigo-600 hover:bg-indigo-500/20">Kode Aktivasi</button>
                  </form>
                <?php endif; ?>
                <form method="post" class="inline" onsubmit="return confirm('Hapus user ini?')">
                  <input type="hidden" name="action" value="delete" />
                  <input type="hidden" name="id" value="<?= $u->id ?>" />
                  <button type="submit" class="rounded-lg bg-red-500/10 px-3 py-1 text-sm font-medium text-red-600 hover:bg-red-500/20">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada data.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Create Modal -->
<div x-show="openCreateUser" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
  <div @click.outside="openCreateUser = false" class="w-full max-w-lg rounded-xl bg-white p-6 dark:bg-gray-900">
    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Tambah Pegawai</h3>
    <form method="post" class="space-y-4">
      <input type="hidden" name="action" value="create" />
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Nama</label>
          <input name="nama" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Username</label>
          <input name="username" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">HP (WhatsApp)</label>
          <input name="phone" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" placeholder="62812xxxx" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Password</label>
          <input type="password" name="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Role</label>
          <select name="role" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required>
            <option value="pegawai">Pegawai</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Ruangan</label>
          <input name="ruangan" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">ASN</label>
          <input name="asn" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">NIK</label>
          <input name="nik" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Status PTKP</label>
          <input name="status_ptkp" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Golongan</label>
          <input name="golongan" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-end gap-2">
        <button type="button" @click="openCreateUser = false" class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Batal</button>
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Simpan</button>
      </div>
    </form>
    <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-800">
      <h4 class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Import Pegawai via CSV</h4>
      <form method="post" enctype="multipart/form-data" class="space-y-3">
        <input type="hidden" name="action" value="import_csv" />
        <input type="file" name="csv_file" accept=".csv" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium hover:file:bg-gray-200 dark:text-gray-300 dark:file:bg-gray-800 dark:hover:file:bg-gray-700" required />
  <p class="text-xs text-gray-500">Header: nik,nama,ruangan,asn,status_ptkp,golongan,username,phone (opsional). User dibuat non-aktif dengan kode aktivasi.</p>
        <div class="flex justify-end">
          <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Import</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div x-show="openEditUser" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
  <div @click.outside="openEditUser = false" class="w-full max-w-lg rounded-xl bg-white p-6 dark:bg-gray-900">
    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Edit Pegawai</h3>
    <form method="post" class="space-y-4">
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="id" :value="editUser.id" />
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Nama</label>
          <input name="nama" :value="editUser.nama" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Username</label>
          <input name="username" :value="editUser.username" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">HP (WhatsApp)</label>
          <input name="phone" :value="editUser.phone" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" placeholder="62812xxxx" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Password (opsional)</label>
          <input type="password" name="password" placeholder="Biarkan kosong jika tidak diubah" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Role</label>
          <select name="role" x-model="editUser.role" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" required>
            <option value="pegawai">Pegawai</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Ruangan</label>
          <input name="ruangan" :value="editUser.ruangan" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">ASN</label>
          <input name="asn" :value="editUser.asn" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">NIK</label>
          <input name="nik" :value="editUser.nik" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Status PTKP</label>
          <input name="status_ptkp" :value="editUser.status_ptkp" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
        <div>
          <label class="mb-1 block text-sm text-gray-600 dark:text-gray-400">Golongan</label>
          <input name="golongan" :value="editUser.golongan" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800" />
        </div>
      </div>
      <div class="mt-6 flex items-center justify-end gap-2">
        <button type="button" @click="openEditUser = false" class="rounded-lg px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">Batal</button>
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Simpan</button>
      </div>
    </form>
  </div>
</div>

</div><script>
  $(document).ready(function() {
    $('#tabelUser').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
