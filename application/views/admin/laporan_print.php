<?php
// Simple printable view for Laporan with RSUD header and table.
// Expect variables: $laporan, $start_date, $end_date
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cetak Laporan Tanda Tangan</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; color:#000; }
    .header { display:flex; align-items:center; gap:16px; border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:16px; }
    .header img { height:64px; }
    .header .title { line-height:1.2; }
    .header .title h1 { font-size:20px; margin:0; }
    .header .title p { margin:2px 0; font-size:12px; }
    .meta { font-size:12px; margin-bottom:10px; }
    table { width:100%; border-collapse:collapse; font-size:12px; }
    th, td { border:1px solid #000; padding:6px; }
    th { background:#f1f1f1; }
    .text-right { text-align:right; }
    .sign { max-height:60px; }
    @media print { @page { size: A4 portrait; margin: 12mm; } }
  </style>
</head>
<body onload="window.print()">
  <div class="header">
    <img src="<?= base_url('assets/images/logo/logo.svg') ?>" alt="Logo RSUD" onerror="this.style.display='none'" />
    <div class="title">
      <h1>RSUD - Laporan Tanda Tangan Jasa/Bonus</h1>
      <p>Periode: <?= $start_date ? html_escape($start_date) : 'Semua' ?> s/d <?= $end_date ? html_escape($end_date) : 'Semua' ?></p>
      <p>Dicetak: <?= date('d M Y H:i') ?></p>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal TTD</th>
        <th>Nama</th>
        <th>Ruangan</th>
        <th>Periode</th>
        <th class="text-right">Sblm Pajak</th>
        <th class="text-right">Pajak 5%</th>
        <th class="text-right">Pajak 15%</th>
        <th class="text-right">Pajak 0%</th>
        <th class="text-right">Stlh Pajak</th>
        <th>Tanda Tangan</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($laporan)):
        // Group by periode (assumes periode is Y-m-d)
        $groups = [];
        foreach ($laporan as $r) {
          $key = isset($r->periode) ? $r->periode : 'Tanpa Periode';
          if (!isset($groups[$key])) { $groups[$key] = []; }
          $groups[$key][] = $r;
        }
        $no=1;
        $grand = ['bruto'=>0,'p5'=>0,'p15'=>0,'p0'=>0,'net'=>0];
        foreach ($groups as $periode => $rows):
          $sub = ['bruto'=>0,'p5'=>0,'p15'=>0,'p0'=>0,'net'=>0];
          foreach ($rows as $row):
            $sub['bruto'] += (float)$row->terima_sebelum_pajak;
            $sub['p5']    += (float)$row->pajak_5;
            $sub['p15']   += (float)$row->pajak_15;
            $sub['p0']    += (float)$row->pajak_0;
            $sub['net']   += (float)$row->terima_setelah_pajak;
          endforeach;
          $grand['bruto'] += $sub['bruto'];
          $grand['p5']    += $sub['p5'];
          $grand['p15']   += $sub['p15'];
          $grand['p0']    += $sub['p0'];
          $grand['net']   += $sub['net'];
      ?>
        <tr>
          <td colspan="11" style="font-weight:bold;background:#fcfcfc;">Periode: <?= html_escape($periode) ?></td>
        </tr>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= html_escape($row->signed_at) ?></td>
            <td><?= html_escape($row->nama) ?></td>
            <td><?= html_escape($row->ruangan) ?></td>
            <td><?= html_escape($row->periode) ?></td>
            <td class="text-right">Rp <?= number_format($row->terima_sebelum_pajak, 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format($row->pajak_5, 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format($row->pajak_15, 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format($row->pajak_0, 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format($row->terima_setelah_pajak, 0, ',', '.') ?></td>
            <td>
              <?php if (!empty($row->tanda_tangan_image)): ?>
                <img class="sign" src="<?= base_url($row->tanda_tangan_image) ?>" alt="ttd" />
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="5" style="text-align:right;font-weight:bold;">Subtotal Periode</td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($sub['bruto'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($sub['p5'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($sub['p15'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($sub['p0'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($sub['net'], 0, ',', '.') ?></td>
          <td></td>
        </tr>
      <?php endforeach; ?>
        <tr>
          <td colspan="5" style="text-align:right;font-weight:bold;">Grand Total</td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($grand['bruto'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($grand['p5'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($grand['p15'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($grand['p0'], 0, ',', '.') ?></td>
          <td class="text-right" style="font-weight:bold;">Rp <?= number_format($grand['net'], 0, ',', '.') ?></td>
          <td></td>
        </tr>
      <?php else: ?>
        <tr><td colspan="11" style="text-align:center; padding:12px;">Tidak ada data.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
