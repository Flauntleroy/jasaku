<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div>
    <h2 class="text-title-md2 font-semibold text-black dark:text-white">Dashboard Pegawai</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Melihat besaran jasa bulan ini dan tanda tangan digital.</p>
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
    <?php if (!empty($pending_current)): ?>
      <div class="col-span-12">
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
          Ada 1 dokumen bulan ini yang menunggu tanda tangan. <a href="<?= base_url('pegawai/history?status=unsigned') ?>" class="font-medium underline">Lihat sekarang</a>.
        </div>
      </div>
    <?php endif; ?>
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
        <?php if (!empty($current_jasa->signed) && $current_jasa->signed): ?>
          <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">Tidak ada dokumen yang perlu ditandatangani</h3>
          <p class="mb-4 text-sm text-gray-600">Semua dokumen bulan ini sudah ditandatangani. Berikut ringkas riwayat terbaru Anda:</p>
          <div class="overflow-hidden rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                  <th class="px-4 py-2">Periode</th>
                  <th class="px-4 py-2">Nilai Bersih</th>
                  <th class="px-4 py-2">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php if (!empty($history_preview)): foreach ($history_preview as $h): ?>
                  <tr>
                    <td class="px-4 py-2 text-sm text-gray-800"><?= html_escape(date('F Y', strtotime($h->periode))) ?></td>
                    <td class="px-4 py-2 text-sm font-medium text-gray-900">Rp <?= number_format($h->terima_setelah_pajak, 0, ',', '.') ?></td>
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
          <div class="mt-3 text-right">
            <a href="<?= base_url('pegawai/history') ?>" class="text-sm font-medium" style="color:#2563eb">Lihat semua riwayat</a>
          </div>
        <?php else: ?>
          <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Tanda Tangan Digital</h3>
            <span class="inline-flex items-center gap-1 rounded-full bg-warning-50 px-2.5 py-1 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">Belum TTD</span>
          </div>
          <form id="signForm" method="post" action="<?= base_url('pegawai/sign') ?>" class="space-y-4">
            <input type="hidden" name="jasa_bonus_id" value="<?= $current_jasa->id ?>" />
            <input type="hidden" name="signature" id="signatureInput" />

            <div class="rounded-lg border border-dashed border-gray-300 p-4 dark:border-gray-700">
              <style>
                /* Ukuran kanvas tetap per perangkat */
                #sigWrap { width: 600px; }
                @media (max-width: 640px) { /* mobile */
                  #sigWrap { width: 340px; }
                }
                /* Safe area indicator */
                #sigWrap.safe-area { position: relative; }
                #sigWrap.safe-area:after {
                  content: "";
                  position: absolute; inset: 10px; border: 1px dashed rgba(0,0,0,.2); pointer-events: none;
                }
              </style>
              <div id="sigWrap" class="mx-auto safe-area">
                <canvas id="sigCanvas" class="rounded bg-white shadow-sm dark:bg-gray-900" height="220"></canvas>
              </div>
              <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Tanda tangani di area putih. Gunakan mouse atau sentuhan.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
              <label class="flex items-center gap-2 text-sm text-gray-600">
                Ketebalan garis:
                <select id="penSize" class="rounded border border-gray-300 px-2 py-1 text-sm">
                  <option value="1.5">Tipis</option>
                  <option value="2" selected>Normal</option>
                  <option value="3">Tebal</option>
                </select>
              </label>
              <button type="button" id="clearBtn" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/5">Bersihkan</button>
              <button type="button" id="undoBtn" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/5">Undo</button>
              <button type="submit" id="submitBtn" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 border border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-900" style="background-color:#2563eb;border-color:#2563eb;">
                <!-- Icon save -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path d="M17 8a1 1 0 10-2 0v5a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h5a1 1 0 100-2H7a4 4 0 00-4 4v6a4 4 0 004 4h6a4 4 0 004-4V8z"/><path d="M9 11.586l6.293-6.293a1 1 0 111.414 1.414l-7 7a1 1 0 01-1.414 0l-3-3A1 1 0 115.707 9.293L9 12.586z"/></svg>
                Simpan Tanda Tangan
              </button>
              <span id="savingHint" class="hidden text-sm text-gray-500">Menyimpan…</span>
            </div>

            <label class="mt-2 flex items-start gap-2 text-sm text-gray-600">
              <input type="checkbox" id="consent" name="consent" class="mt-0.5" value="1" />
              <span>Saya menyetujui bahwa tanda tangan elektronik ini sah dan mengikat untuk periode di atas.</span>
            </label>

            <!-- Tombol simpan tambahan agar selalu terlihat (mobile/viewport sempit)
            <div class="mt-2">
              <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 border border-primary sm:hidden" style="background-color:#2563eb;border-color:#2563eb;">Simpan Tanda Tangan</button>
            </div> -->
          </form>

          <script>
            (function(){
              const canvas = document.getElementById('sigCanvas');
              const wrap = document.getElementById('sigWrap');
              const form = document.getElementById('signForm');
              const input = document.getElementById('signatureInput');
              const clearBtn = document.getElementById('clearBtn');
              const undoBtn = document.getElementById('undoBtn');
              const submitBtn = document.getElementById('submitBtn');
              const savingHint = document.getElementById('savingHint');
              const penSize = document.getElementById('penSize');
              const consent = document.getElementById('consent');
              if(!canvas) return;
              const ctx = canvas.getContext('2d');
              const DPR = window.devicePixelRatio || 1;
              const ASPECT = 600/220; // Rasio aspek tetap
              function resize() {
                const cssWidth = (wrap && wrap.clientWidth) ? wrap.clientWidth : 600;
                const cssHeight = Math.round(cssWidth / ASPECT);
                canvas.width = Math.floor(cssWidth * DPR);
                canvas.height = Math.floor(cssHeight * DPR);
                canvas.style.width = cssWidth + 'px';
                canvas.style.height = cssHeight + 'px';
                // Reset transform setiap resize, lalu scale sesuai DPR
                ctx.setTransform(1,0,0,1,0,0);
                ctx.scale(DPR, DPR);
                ctx.lineWidth = parseFloat(penSize.value || '2');
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#111827';
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0,0,canvas.width,canvas.height);
              }
              resize();
              window.addEventListener('resize', resize);

              let drawing = false;
              let last = null;
              let drewSomething = false;
              const strokes = []; // list of paths; each path is array of points
              let currentPath = null;
              function getPos(e){
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return { x: clientX - rect.left, y: clientY - rect.top };
              }
              function start(e){ drawing = true; last = getPos(e); currentPath = [last]; e.preventDefault(); }
              function move(e){ if(!drawing) return; const p = getPos(e); ctx.beginPath(); ctx.moveTo(last.x, last.y); ctx.lineTo(p.x, p.y); ctx.stroke(); last = p; currentPath.push(p); drewSomething = true; e.preventDefault(); }
              function end(){ if(drawing && currentPath){ strokes.push({points: currentPath, width: parseFloat(penSize.value||'2')}); } drawing = false; currentPath = null; }

              canvas.addEventListener('mousedown', start);
              canvas.addEventListener('mousemove', move);
              window.addEventListener('mouseup', end);
              canvas.addEventListener('touchstart', start, {passive:false});
              canvas.addEventListener('touchmove', move, {passive:false});
              canvas.addEventListener('touchend', end);

              clearBtn.addEventListener('click', function(){
                ctx.save();
                ctx.setTransform(1,0,0,1,0,0);
                ctx.clearRect(0,0,canvas.width,canvas.height);
                ctx.restore();
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0,0,canvas.width,canvas.height);
                drewSomething = false;
                strokes.length = 0;
              });

              function redrawAll(){
                ctx.save();
                ctx.setTransform(1,0,0,1,0,0);
                ctx.clearRect(0,0,canvas.width,canvas.height);
                ctx.restore();
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0,0,canvas.width,canvas.height);
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#111827';
                for(const path of strokes){
                  ctx.lineWidth = path.width;
                  for(let i=1;i<path.points.length;i++){
                    const a = path.points[i-1], b = path.points[i];
                    ctx.beginPath(); ctx.moveTo(a.x,a.y); ctx.lineTo(b.x,b.y); ctx.stroke();
                  }
                }
              }

              undoBtn.addEventListener('click', function(){
                if(strokes.length>0){ strokes.pop(); redrawAll(); }
                drewSomething = strokes.length>0;
              });

              penSize.addEventListener('change', function(){
                ctx.lineWidth = parseFloat(penSize.value || '2');
              });

              form.addEventListener('submit', function(e){
                if (!drewSomething) {
                  alert('Silakan tanda tangani di area kanvas terlebih dahulu.');
                  e.preventDefault();
                  return;
                }
                if (!consent.checked) {
                  alert('Anda harus menyetujui pernyataan sebelum menyimpan tanda tangan.');
                  e.preventDefault();
                  return;
                }
                // Convert to PNG data URL and store into hidden input
                try {
                  // Normalize export to fixed size 600x220 with white background
                  const exportW = 600, exportH = 220;
                  const out = document.createElement('canvas');
                  out.width = exportW; out.height = exportH;
                  const octx = out.getContext('2d');
                  // Fill white background
                  octx.fillStyle = '#ffffff';
                  octx.fillRect(0,0,exportW,exportH);
                  // Aspect-fit scale letterboxed
                  const srcW = canvas.width, srcH = canvas.height;
                  const scale = Math.min(exportW / srcW, exportH / srcH);
                  const drawW = Math.floor(srcW * scale);
                  const drawH = Math.floor(srcH * scale);
                  const dx = Math.floor((exportW - drawW)/2);
                  const dy = Math.floor((exportH - drawH)/2);
                  octx.imageSmoothingEnabled = true;
                  octx.imageSmoothingQuality = 'high';
                  octx.drawImage(canvas, 0, 0, srcW, srcH, dx, dy, drawW, drawH);
                  input.value = out.toDataURL('image/png');
                  // Disable all submit buttons (including mobile fallback)
                  const submitButtons = form.querySelectorAll('button[type="submit"]');
                  submitButtons.forEach(btn => btn.classList.add('opacity-60','cursor-not-allowed'));
                  submitButtons.forEach(btn => btn.disabled = true);
                  savingHint.classList.remove('hidden');
                } catch(err) {
                  alert('Browser tidak mendukung ekspor tanda tangan.');
                  e.preventDefault();
                }
              });
            })();
          </script>
        <?php endif; ?>
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
