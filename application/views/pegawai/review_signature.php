<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="rounded-2xl border border-emerald-300 bg-emerald-50 p-6 text-emerald-800 dark:border-emerald-500/50 dark:bg-emerald-500/10 dark:text-white">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Review Tanda Tangan</h2>
        <p class="text-gray-600 dark:text-white">Tanda tangan Anda telah berhasil disimpan</p>
    </div>

    <div class="border rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="font-semibold text-gray-700 mb-2 dark:text-white">Detail Dokumen:</h3>
                <table class="w-full">
                    <tr>
                        <td class="py-1 text-gray-600 dark:text-white">Periode</td>
                        <td class="py-1 font-medium dark:text-white">: <?= $jasa->periode ?></td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600 dark:text-white">Bruto</td>
                        <td class="py-1 font-medium dark:text-white">: Rp <?= number_format($jasa->terima_sebelum_pajak, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600 dark:text-white">Pajak</td>
                        <td class="py-1 font-medium dark:text-white">: Rp <?= number_format($jasa->terima_sebelum_pajak - $jasa->terima_setelah_pajak, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600 dark:text-white">Netto</td>
                        <td class="py-1 font-medium dark:text-white">: Rp <?= number_format($jasa->terima_setelah_pajak, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="py-1 text-gray-600 dark:text-white">Ditandatangani</td>
                        <td class="py-1 font-medium dark:text-white">: <?= date('d M Y H:i', strtotime($tanda_tangan->signed_at)) ?></td>   
                    </tr>
                </table>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 mb-2 dark:text-white">Tanda Tangan Anda:</h3>
                <div class="border border-emerald-300 rounded-lg p-4 bg-gray-50 flex items-center justify-center ">
                    <img src="<?= base_url($tanda_tangan->tanda_tangan_image) ?>" alt="Tanda Tangan" class="max-w-full max-h-48">
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-center gap-4 mt-6">
        <a href="<?= site_url('pegawai/dashboard') ?>" class="bg-primary hover:bg-primary/90 text-white font-medium py-2 px-6 rounded-lg transition duration-200 text-center" style="background-color:#0345b0;border-color:#02317e;">
            Selesai
        </a>
        <a href="<?= site_url('pegawai/tanda-tangan-ulang/' . $jasa->id) ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition duration-200 text-center">
            Tanda Tangan Ulang
        </a>
    </div>
</div>