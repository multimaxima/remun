<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('index');
/*Route::get('reset', [App\Http\Controllers\IndexController::class, 'reset'])->name('reset');*/

Auth::routes();

Route::get('/beranda', [App\Http\Controllers\HomeController::class, 'index'])->name('beranda');
Route::get('/parameter-rumah-sakit', [App\Http\Controllers\HomeController::class, 'parameter'])->name('parameter');
Route::post('/parameter', [App\Http\Controllers\HomeController::class, 'parameter_simpan'])->name('parameter_simpan');

Route::get('/informasi-software', [App\Http\Controllers\HomeController::class, 'informasi_software'])->name('informasi_software');

Route::get('/rumusan-indeks', [App\Http\Controllers\KaryawanController::class, 'rumusan_indeks'])->name('rumusan_indeks');
Route::get('/rumusan-indeks-show', [App\Http\Controllers\KaryawanController::class, 'rumusan_indeks_simpan_show'])->name('rumusan_indeks_simpan_show');
Route::post('/rumusan-indeks', [App\Http\Controllers\KaryawanController::class, 'rumusan_indeks_simpan'])->name('rumusan_indeks_simpan');

Route::get('/pilih_propinsi', [App\Http\Controllers\HomeController::class, 'pilih_propinsi'])->name('pilih_propinsi');
Route::get('/pilih_kota', [App\Http\Controllers\HomeController::class, 'pilih_kota'])->name('pilih_kota');
Route::get('/pilih_kecamatan', [App\Http\Controllers\HomeController::class, 'pilih_kecamatan'])->name('pilih_kecamatan');
Route::get('/pilih_desa', [App\Http\Controllers\HomeController::class, 'pilih_desa'])->name('pilih_desa');

Route::get('/pilih_propinsi_edit', [App\Http\Controllers\HomeController::class, 'pilih_propinsi_edit'])->name('pilih_propinsi_edit');
Route::get('/pilih_kota_edit', [App\Http\Controllers\HomeController::class, 'pilih_kota_edit'])->name('pilih_kota_edit');
Route::get('/pilih_kecamatan_edit', [App\Http\Controllers\HomeController::class, 'pilih_kecamatan_edit'])->name('pilih_kecamatan_edit');
Route::get('/pilih_desa_edit', [App\Http\Controllers\HomeController::class, 'pilih_desa_edit'])->name('pilih_desa_edit');

Route::get('/menu/{id}', [App\Http\Controllers\HomeController::class, 'menu'])->name('menu');
Route::post('/menu', [App\Http\Controllers\HomeController::class, 'menu_simpan'])->name('menu_simpan');

Route::get('/parameter-remunerasi', [App\Http\Controllers\HomeController::class, 'parameter_software'])->name('parameter_software');
Route::post('/parameter-software', [App\Http\Controllers\HomeController::class, 'parameter_software_simpan'])->name('parameter_software_simpan');

Route::get('/profil', [App\Http\Controllers\HomeController::class, 'profil'])->name('profil');
Route::post('/profil', [App\Http\Controllers\HomeController::class, 'profil_simpan'])->name('profil_simpan');
Route::get('/profil-password', [App\Http\Controllers\HomeController::class, 'profil_password_form'])->name('profil_password_form');
Route::post('/profil-password', [App\Http\Controllers\HomeController::class, 'profil_password'])->name('profil_password');

Route::get('/database', [App\Http\Controllers\DataController::class, 'database'])->name('database');

Route::get('/karyawan', [App\Http\Controllers\KaryawanController::class, 'karyawan'])->name('karyawan');
Route::get('/karyawan/{id}', [App\Http\Controllers\KaryawanController::class, 'karyawan_hapus'])->name('karyawan_hapus');
Route::post('/karyawan-update-history', [App\Http\Controllers\KaryawanController::class, 'karyawan_update_history'])->name('karyawan_update_history');
Route::post('/karyawan-update-history-ulang', [App\Http\Controllers\KaryawanController::class, 'karyawan_update_history_ulang'])->name('karyawan_update_history_ulang');
Route::get('/karyawan-cuti-simpan', [App\Http\Controllers\KaryawanController::class, 'karyawan_cuti_simpan'])->name('karyawan_cuti_simpan');
Route::get('/karyawan-reset/{id}', [App\Http\Controllers\KaryawanController::class, 'karyawan_reset'])->name('karyawan_reset');
Route::get('/karyawan-edit', [App\Http\Controllers\KaryawanController::class, 'karyawan_edit'])->name('karyawan_edit');
Route::post('/karyawan-edit', [App\Http\Controllers\KaryawanController::class, 'karyawan_edit_simpan'])->name('karyawan_edit_simpan');
Route::post('/karyawan-edit-jasa-indek', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_indek_simpan'])->name('karyawan_jasa_indek_simpan');
Route::get('/karyawan-cetak', [App\Http\Controllers\KaryawanController::class, 'karyawan_cetak'])->name('karyawan_cetak');

Route::get('/karyawan-backup', [App\Http\Controllers\KaryawanController::class, 'karyawan_backup'])->name('karyawan_backup');

Route::get('/karyawan-cuti', [App\Http\Controllers\KaryawanController::class, 'karyawan_cuti'])->name('karyawan_cuti');
Route::get('/karyawan-cuti/{id}', [App\Http\Controllers\KaryawanController::class, 'karyawan_cuti_hapus'])->name('karyawan_cuti_hapus');
Route::post('/karyawan-cuti-baru', [App\Http\Controllers\KaryawanController::class, 'karyawan_cuti_baru'])->name('karyawan_cuti_baru');
Route::get('/karyawan-cuti-edit-show', [App\Http\Controllers\KaryawanController::class, 'karyawan_cuti_edit_show'])->name('karyawan_cuti_edit_show');
Route::post('/karyawan-cuti-edit', [App\Http\Controllers\KaryawanController::class, 'karyawan_cuti_edit'])->name('karyawan_cuti_edit');

Route::get('/karyawan-jasa', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa'])->name('karyawan_jasa');
Route::get('/karyawan-jasa-show', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_simpan_show'])->name('karyawan_jasa_simpan_show');
Route::post('/karyawan-jasa', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_simpan'])->name('karyawan_jasa_simpan');
Route::get('/karyawan-jasa-cetak', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_cetak'])->name('karyawan_jasa_cetak');
Route::get('/karyawan-jasa-export', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_export'])->name('karyawan_jasa_export');
Route::get('/karyawan-jasa/{id}', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_edit'])->name('karyawan_jasa_edit');

Route::get('/karyawan-jasa-history', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_history'])->name('karyawan_jasa_history');
Route::post('/karyawan-jasa-history-simpan', [App\Http\Controllers\KaryawanController::class, 'karyawan_jasa_history_simpan'])->name('karyawan_jasa_history_simpan');

Route::get('/karyawan-indeks', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks'])->name('karyawan_indeks');
Route::get('/karyawan-indeks-show', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_simpan_show'])->name('karyawan_indeks_simpan_show');
Route::post('/karyawan-indeks', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_simpan'])->name('karyawan_indeks_simpan');
Route::get('/karyawan-indeks-cetak', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_cetak'])->name('karyawan_indeks_cetak');
Route::get('/karyawan-indeks-export', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_export'])->name('karyawan_indeks_export');

Route::get('/karyawan-indeks/{id}', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_edit'])->name('karyawan_indeks_edit');

Route::get('/karyawan-indeks-history', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_history'])->name('karyawan_indeks_history');
Route::get('/karyawan-indeks-edit-show', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_edit_show'])->name('karyawan_indeks_edit_show');
Route::post('/karyawan-indeks-edit-simpan', [App\Http\Controllers\KaryawanController::class, 'karyawan_indeks_edit_simpan'])->name('karyawan_indeks_edit_simpan');

Route::get('/karyawan-baru', [App\Http\Controllers\KaryawanController::class, 'karyawan_baru'])->name('karyawan_baru');
Route::post('/karyawan-baru', [App\Http\Controllers\KaryawanController::class, 'karyawan_baru_simpan'])->name('karyawan_baru_simpan');

Route::get('/karyawan-gapok', [App\Http\Controllers\KaryawanController::class, 'karyawan_gapok'])->name('karyawan_gapok');
Route::get('/karyawan-gapok-show', [App\Http\Controllers\KaryawanController::class, 'karyawan_gapok_simpan_show'])->name('karyawan_gapok_simpan_show');
Route::post('/karyawan-gapok', [App\Http\Controllers\KaryawanController::class, 'karyawan_gapok_simpan'])->name('karyawan_gapok_simpan');
Route::get('/karyawan-gapok/{id}', [App\Http\Controllers\KaryawanController::class, 'karyawan_gapok_edit'])->name('karyawan_gapok_edit');

Route::get('/karyawan-export', [App\Http\Controllers\KaryawanController::class, 'karyawan_export'])->name('karyawan_export');

Route::get('/karyawan-histori', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori'])->name('karyawan_histori');
Route::post('/karyawan-histori-cuti', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_cuti'])->name('karyawan_histori_cuti');
Route::post('/karyawan-histori-absen', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_absen'])->name('karyawan_histori_absen');
Route::post('/karyawan-histori-pindah', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_pindah'])->name('karyawan_histori_pindah');
Route::post('/karyawan-histori-tambahan-1', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_tambahan_1'])->name('karyawan_histori_tambahan_1');
Route::post('/karyawan-histori-tambahan-2', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_tambahan_2'])->name('karyawan_histori_tambahan_2');

Route::get('/karyawan-absensi', [App\Http\Controllers\KaryawanController::class, 'karyawan_absensi'])->name('karyawan_absensi');
Route::get('/karyawan-absensi-cuti', [App\Http\Controllers\KaryawanController::class, 'karyawan_absen_cuti'])->name('karyawan_absen_cuti');
Route::get('/karyawan-absensi-hadir', [App\Http\Controllers\KaryawanController::class, 'karyawan_absen_hadir'])->name('karyawan_absen_hadir');

Route::get('/history-karyawan', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_admin'])->name('karyawan_histori_admin');

Route::get('/karyawan-histori-update', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_update'])->name('karyawan_histori_update');
Route::get('/karyawan-histori-data', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_data'])->name('karyawan_histori_data');
Route::get('/karyawan-his-cuti', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_cuti'])->name('karyawan_his_cuti');
Route::get('/karyawan-his-absen', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_absen'])->name('karyawan_his_absen');
Route::get('/karyawan-his-ruang', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_ruang'])->name('karyawan_his_ruang');
Route::get('/karyawan-his-ruang_1', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_ruang_1'])->name('karyawan_his_ruang_1');
Route::get('/karyawan-his-ruang_2', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_ruang_2'])->name('karyawan_his_ruang_2');
Route::post('/karyawan-his-cuti-periode', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_cuti_periode'])->name('karyawan_his_cuti_periode');
Route::post('/karyawan-his-hadir-periode', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_hadir_periode'])->name('karyawan_his_hadir_periode');
Route::post('/karyawan-his-ruang-periode', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_ruang_periode'])->name('karyawan_his_ruang_periode');
Route::post('/karyawan-his-ruang_1-periode', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_ruang_1_periode'])->name('karyawan_his_ruang_1_periode');
Route::post('/karyawan-his-ruang-2_periode', [App\Http\Controllers\KaryawanController::class, 'karyawan_his_ruang_2_periode'])->name('karyawan_his_ruang_2_periode');

Route::get('/absensi-karyawan', [App\Http\Controllers\KaryawanController::class, 'karyawan_ruang'])->name('karyawan_ruang');
Route::get('/absensi-kehadiran', [App\Http\Controllers\KaryawanController::class, 'karyawan_hadir'])->name('karyawan_hadir');
Route::get('/absensi-pindah', [App\Http\Controllers\KaryawanController::class, 'karyawan_pindah_show'])->name('karyawan_pindah_show');
Route::post('/absensi-pindah', [App\Http\Controllers\KaryawanController::class, 'karyawan_pindah_ruang'])->name('karyawan_pindah_ruang');

Route::get('/cuti-hapus/{id}', [App\Http\Controllers\KaryawanController::class, 'cuti_hapus'])->name('cuti_hapus');
Route::post('/cuti-baru', [App\Http\Controllers\KaryawanController::class, 'cuti_baru'])->name('cuti_baru');
Route::get('/cuti-edit-show', [App\Http\Controllers\KaryawanController::class, 'cuti_edit_show'])->name('cuti_edit_show');
Route::post('/cuti-edit', [App\Http\Controllers\KaryawanController::class, 'cuti_edit'])->name('cuti_edit');

Route::get('/data-bank', [App\Http\Controllers\HomeController::class, 'bank'])->name('bank');
Route::get('/bank/{id}', [App\Http\Controllers\HomeController::class, 'bank_hapus'])->name('bank_hapus');
Route::post('/bank-baru', [App\Http\Controllers\HomeController::class, 'bank_baru'])->name('bank_baru');
Route::get('/bank-edit-show', [App\Http\Controllers\HomeController::class, 'bank_edit_show'])->name('bank_edit_show');
Route::post('/bank-edit', [App\Http\Controllers\HomeController::class, 'bank_edit'])->name('bank_edit');

Route::get('/data-ruang', [App\Http\Controllers\HomeController::class, 'ruang'])->name('ruang');
Route::get('/ruang/{id}', [App\Http\Controllers\HomeController::class, 'ruang_hapus'])->name('ruang_hapus');
Route::post('/ruang-baru', [App\Http\Controllers\HomeController::class, 'ruang_baru'])->name('ruang_baru');
Route::get('/ruang-editing', [App\Http\Controllers\HomeController::class, 'ruang_editing'])->name('ruang_editing');
Route::post('/ruang-edit', [App\Http\Controllers\HomeController::class, 'ruang_edit'])->name('ruang_edit');

Route::get('/edit-tarif', [App\Http\Controllers\LayananController::class, 'edit_tarif'])->name('edit_tarif');
Route::get('/edit-tarif-hapus/{id}', [App\Http\Controllers\LayananController::class, 'edit_tarif_hapus'])->name('edit_tarif_hapus');
Route::post('/edit-tarif-kolektif', [App\Http\Controllers\LayananController::class, 'edit_tarif_kolektif'])->name('edit_tarif_kolektif');
Route::post('/edit-tarif-simpan', [App\Http\Controllers\LayananController::class, 'edit_tarif_simpan'])->name('edit_tarif_simpan');

Route::get('/ruang-layanan/{id}', [App\Http\Controllers\HomeController::class, 'ruang_layanan'])->name('ruang_layanan');
Route::get('/ruang-layanan-hapus/{id}', [App\Http\Controllers\HomeController::class, 'ruang_layanan_hapus'])->name('ruang_layanan_hapus');
Route::get('/ruang-layanan-baru-show', [App\Http\Controllers\HomeController::class, 'ruang_layanan_baru_show'])->name('ruang_layanan_baru_show');
Route::post('/ruang-layanan-baru', [App\Http\Controllers\HomeController::class, 'ruang_layanan_baru'])->name('ruang_layanan_baru');
Route::get('/ruang-layanan-editing', [App\Http\Controllers\HomeController::class, 'ruang_layanan_editing'])->name('ruang_layanan_editing');
Route::post('/ruang-layanan-edit', [App\Http\Controllers\HomeController::class, 'ruang_layanan_edit'])->name('ruang_layanan_edit');

Route::get('/ruang-jenis', [App\Http\Controllers\HomeController::class, 'ruang_jenis'])->name('ruang_jenis');

Route::get('/jasa-layanan', [App\Http\Controllers\LayananController::class, 'jasa_layanan'])->name('jasa_layanan');
Route::get('/jasa-layanan/{id}', [App\Http\Controllers\LayananController::class, 'jasa_layanan_hapus'])->name('jasa_layanan_hapus');
Route::post('/jasa-layanan-baru', [App\Http\Controllers\LayananController::class, 'jasa_layanan_baru'])->name('jasa_layanan_baru');
Route::get('/jasa-layanan-edit-show', [App\Http\Controllers\LayananController::class, 'jasa_layanan_edit_show'])->name('jasa_layanan_edit_show');
Route::post('/jasa-layanan-edit', [App\Http\Controllers\LayananController::class, 'jasa_layanan_edit'])->name('jasa_layanan_edit');

Route::get('/kategori-layanan', [App\Http\Controllers\LayananController::class, 'kategori_layanan'])->name('kategori_layanan');
Route::get('/kategori-layanan/{id}', [App\Http\Controllers\LayananController::class, 'kategori_layanan_hapus'])->name('kategori_layanan_hapus');
Route::post('/kategori-layanan-baru', [App\Http\Controllers\LayananController::class, 'kategori_layanan_baru'])->name('kategori_layanan_baru');
Route::get('/kategori-layanan-edit-show', [App\Http\Controllers\LayananController::class, 'kategori_layanan_edit_show'])->name('kategori_layanan_edit_show');
Route::post('/kategori-layanan-edit', [App\Http\Controllers\LayananController::class, 'kategori_layanan_edit'])->name('kategori_layanan_edit');

Route::get('/rekening-tarif', [App\Http\Controllers\LayananController::class, 'rekening_layanan'])->name('rekening_layanan');
Route::get('/rekening-layanan/{id}', [App\Http\Controllers\LayananController::class, 'rekening_layanan_hapus'])->name('rekening_layanan_hapus');
Route::post('/rekening-layanan-baru', [App\Http\Controllers\LayananController::class, 'rekening_layanan_baru'])->name('rekening_layanan_baru');
Route::get('/rekening-layanan-edit-show', [App\Http\Controllers\LayananController::class, 'rekening_layanan_edit_show'])->name('rekening_layanan_edit_show');
Route::post('/rekening-layanan-edit', [App\Http\Controllers\LayananController::class, 'rekening_layanan_edit'])->name('rekening_layanan_edit');

Route::get('/bagian', [App\Http\Controllers\LayananController::class, 'bagian'])->name('bagian');

Route::get('/jenis-tenaga', [App\Http\Controllers\LayananController::class, 'bagian_tenaga'])->name('bagian_tenaga');
Route::get('/bagian-tenaga/{id}', [App\Http\Controllers\LayananController::class, 'bagian_tenaga_hapus'])->name('bagian_tenaga_hapus');
Route::post('/bagian-tenaga-baru', [App\Http\Controllers\LayananController::class, 'bagian_tenaga_baru'])->name('bagian_tenaga_baru');
Route::get('/bagian-tenaga-edit-show', [App\Http\Controllers\LayananController::class, 'bagian_tenaga_edit_show'])->name('bagian_tenaga_edit_show');
Route::post('/bagian-tenaga-edit', [App\Http\Controllers\LayananController::class, 'bagian_tenaga_edit'])->name('bagian_tenaga_edit');

Route::get('/bagian-hapus/{id}', [App\Http\Controllers\LayananController::class, 'bagian_hapus'])->name('bagian_hapus');
Route::post('/bagian-baru', [App\Http\Controllers\LayananController::class, 'bagian_baru'])->name('bagian_baru');
Route::get('/bagian-edit-show', [App\Http\Controllers\LayananController::class, 'bagian_edit_show'])->name('bagian_edit_show');
Route::post('/bagian-edit', [App\Http\Controllers\LayananController::class, 'bagian_edit'])->name('bagian_edit');

Route::get('/daftar-tarif', [App\Http\Controllers\LayananController::class, 'tarif_daftar'])->name('tarif_daftar');
Route::post('/salin-tarif', [App\Http\Controllers\LayananController::class, 'tarif_salin'])->name('tarif_salin');
Route::get('/skema-tarif', [App\Http\Controllers\LayananController::class, 'tarif_user'])->name('tarif_user');
Route::get('/skema-tarif-cetak', [App\Http\Controllers\LayananController::class, 'tarif_cetak'])->name('tarif_cetak');

Route::get('/tarif', [App\Http\Controllers\LayananController::class, 'tarif'])->name('tarif');
Route::get('/tarif/1/{id}', [App\Http\Controllers\LayananController::class, 'tarif_1'])->name('tarif_1');
Route::get('/tarif/2/{id}', [App\Http\Controllers\LayananController::class, 'tarif_2'])->name('tarif_2');
Route::get('/tarif/3/{id}', [App\Http\Controllers\LayananController::class, 'tarif_3'])->name('tarif_3');

Route::get('/tarif-1-hapus/{id}', [App\Http\Controllers\LayananController::class, 'tarif_1_hapus'])->name('tarif_1_hapus');
Route::post('/tarif-1-baru', [App\Http\Controllers\LayananController::class, 'tarif_1_baru'])->name('tarif_1_baru');
Route::post('/tarif-1-edit', [App\Http\Controllers\LayananController::class, 'tarif_1_edit'])->name('tarif_1_edit');

Route::get('/tarif-2-hapus/{id}', [App\Http\Controllers\LayananController::class, 'tarif_2_hapus'])->name('tarif_2_hapus');
Route::post('/tarif-2-baru', [App\Http\Controllers\LayananController::class, 'tarif_2_baru'])->name('tarif_2_baru');
Route::post('/tarif-2-edit', [App\Http\Controllers\LayananController::class, 'tarif_2_edit'])->name('tarif_2_edit');

Route::get('/tarif-3-hapus/{id}', [App\Http\Controllers\LayananController::class, 'tarif_3_hapus'])->name('tarif_3_hapus');
Route::post('/tarif-3-baru', [App\Http\Controllers\LayananController::class, 'tarif_3_baru'])->name('tarif_3_baru');
Route::post('/tarif-3-edit', [App\Http\Controllers\LayananController::class, 'tarif_3_edit'])->name('tarif_3_edit');

Route::get('/tarif-4-hapus/{id}', [App\Http\Controllers\LayananController::class, 'tarif_4_hapus'])->name('tarif_4_hapus');
Route::post('/tarif-4-baru', [App\Http\Controllers\LayananController::class, 'tarif_4_baru'])->name('tarif_4_baru');
Route::post('/tarif-4-edit', [App\Http\Controllers\LayananController::class, 'tarif_4_edit'])->name('tarif_4_edit');

Route::get('/kalkulasi_ulang', [App\Http\Controllers\LayananController::class, 'kalkulasi_ulang'])->name('kalkulasi_ulang');

Route::get('/pasien', [App\Http\Controllers\PasienController::class, 'pasien'])->name('pasien');
Route::get('/pasien-export', [App\Http\Controllers\PasienController::class, 'pasien_export'])->name('pasien_export');
Route::get('/pasien-detil', [App\Http\Controllers\PasienController::class, 'pasien_detil'])->name('pasien_detil');
Route::get('/pasien-keluar', [App\Http\Controllers\PasienController::class, 'pasien_keluar'])->name('pasien_keluar');
Route::get('/pasien-keluar-dpjp', [App\Http\Controllers\PasienController::class, 'pasien_keluar_dpjp'])->name('pasien_keluar_dpjp');
Route::post('/pasien-keluar-dpjp', [App\Http\Controllers\PasienController::class, 'pasien_keluar_dpjp_simpan'])->name('pasien_keluar_dpjp_simpan');
Route::get('/pasien-keluar-jenis', [App\Http\Controllers\PasienController::class, 'pasien_keluar_jenis'])->name('pasien_keluar_jenis');
Route::post('/pasien-keluar-jenis', [App\Http\Controllers\PasienController::class, 'pasien_keluar_jenis_simpan'])->name('pasien_keluar_jenis_simpan');
Route::get('/pasien-keluar-detil', [App\Http\Controllers\PasienController::class, 'pasien_keluar_detil'])->name('pasien_keluar_detil');

Route::get('/pasien-keluar-rincian-dpjp', [App\Http\Controllers\PasienController::class, 'pasien_keluar_rincian_dpjp'])->name('pasien_keluar_rincian_dpjp');
Route::get('/pasien-keluar-rincian-dpjp-export', [App\Http\Controllers\PasienController::class, 'pasien_keluar_rincian_dpjp_export'])->name('pasien_keluar_rincian_dpjp_export');

Route::get('/pasien-per-ruang', [App\Http\Controllers\PasienController::class, 'pasien_per_ruang'])->name('pasien_per_ruang');

Route::get('/cari-pasien', [App\Http\Controllers\PasienController::class, 'pasien_cari'])->name('pasien_cari');

Route::get('/claim-bpjs/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs'])->name('bpjs');
Route::get('/data-claim-asuransi', [App\Http\Controllers\BPJSController::class, 'bpjs_data'])->name('bpjs_data');
Route::get('/claim-bpjs-data-detil/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_data_detil'])->name('bpjs_data_detil');
Route::get('/claim-bpjs-data/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_data_hapus'])->name('bpjs_data_hapus');
Route::post('/bpjs-ambil-data', [App\Http\Controllers\BPJSController::class, 'ambil_data'])->name('ambil_data');
Route::get('/bpjs-salin-inap/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_salin_inap'])->name('bpjs_salin_inap');
Route::get('/bpjs-salin-jalan/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_salin_jalan'])->name('bpjs_salin_jalan');
Route::get('/bpjs-claim-seimbang/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_seimbang'])->name('bpjs_seimbang');
Route::get('/bpjs-batal/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_batal'])->name('bpjs_batal');
Route::post('/bpjs-claim', [App\Http\Controllers\BPJSController::class, 'bpjs_claim'])->name('bpjs_claim');
Route::get('/bpjs-edit-show', [App\Http\Controllers\BPJSController::class, 'bpjs_edit_show'])->name('bpjs_edit_show');
Route::get('/bpjs-rincian/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_rincian'])->name('bpjs_rincian');
Route::get('/bpjs-rincian-cetak/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_rincian_cetak'])->name('bpjs_rincian_cetak');
Route::get('/bpjs-ok/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_ok'])->name('bpjs_ok');
Route::get('/bpjs-cetak/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_cetak'])->name('bpjs_cetak');
Route::get('/bpjs-export/{id}', [App\Http\Controllers\BPJSController::class, 'bpjs_export'])->name('bpjs_export');
Route::get('/bpjs-jalan', [App\Http\Controllers\BPJSController::class, 'bpjs_jalan'])->name('bpjs_jalan');
Route::get('/bpjs-inap', [App\Http\Controllers\BPJSController::class, 'bpjs_inap'])->name('bpjs_inap');
Route::get('/bpjs-claim', [App\Http\Controllers\BPJSController::class, 'bpjs_admin'])->name('bpjs_admin');

Route::get('/remunerasi', [App\Http\Controllers\RemunerasiController::class, 'remunerasi'])->name('remunerasi');
Route::get('/remunerasi-admin', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_admin'])->name('remunerasi_admin');
Route::get('/remunerasi-batal/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_batal'])->name('remunerasi_batal');
Route::get('/remunerasi-reset/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_reset'])->name('remunerasi_reset');
Route::get('/remunerasi-reset-admin/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_reset_admin'])->name('remunerasi_reset_admin');
Route::post('/remunerasi-hitung', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_hitung'])->name('remunerasi_hitung');
Route::get('/remunerasi-hitung/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_hitung_penyesuaian'])->name('remunerasi_hitung_penyesuaian');

Route::get('/remunerasi-jasa', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_jasa'])->name('remunerasi_jasa');
Route::get('/remunerasi-tandon/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_tandon'])->name('remunerasi_tandon');
Route::get('/remunerasi-jasa-tampil', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_jasa_tampil'])->name('remunerasi_jasa_tampil');
Route::post('/remunerasi-jasa-relokasi', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_jasa_relokasi'])->name('remunerasi_jasa_relokasi');
Route::post('/remunerasi-jasa-alokasi', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_jasa_alokasi'])->name('remunerasi_jasa_alokasi');

Route::post('/remunerasi-jasa-edit', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_jasa_edit'])->name('remunerasi_jasa_edit');

Route::get('/remunerasi-edit-jasa-tambahan', [App\Http\Controllers\RemunerasiController::class, 'edit_jasa_tambahan'])->name('edit_jasa_tambahan');

Route::post('/relokasi-apotik', [App\Http\Controllers\RemunerasiController::class, 'relokasi_apotik'])->name('relokasi_apotik');
Route::get('/reset-apotik/{id}', [App\Http\Controllers\RemunerasiController::class, 'reset_apotik'])->name('reset_apotik');
Route::post('/tambahan-jasa', [App\Http\Controllers\RemunerasiController::class, 'tambahan_jasa'])->name('tambahan_jasa');

Route::get('/remunerasi-farmasi/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_farmasi'])->name('remunerasi_farmasi');
Route::get('/remunerasi-farmasi-jasa', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_farmasi_jasa'])->name('remunerasi_farmasi_jasa');
Route::post('/remunerasi-farmasi-simpan', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_farmasi_simpan'])->name('remunerasi_farmasi_simpan');

Route::get('/remunerasi-umum/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_umum'])->name('remunerasi_umum');
Route::get('/remunerasi-umum-jasa', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_umum_jasa'])->name('remunerasi_umum_jasa');
Route::post('/remunerasi-umum-simpan', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_umum_simpan'])->name('remunerasi_umum_simpan');

Route::get('/remunerasi-detil/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_detil'])->name('remunerasi_detil');
Route::get('/remunerasi-detil-cetak/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_detil_cetak'])->name('remunerasi_detil_cetak');
Route::get('/remunerasi-ok/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_ok'])->name('remunerasi_ok');
Route::post('/remunerasi-edit', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_edit'])->name('remunerasi_edit');
Route::get('/remunerasi-edit-show', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_edit_show'])->name('remunerasi_edit_show');
Route::post('/remunerasi-admin-edit', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_admin_edit'])->name('remunerasi_admin_edit');
Route::get('/remunerasi-admin-edit-show', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_admin_edit_show'])->name('remunerasi_admin_edit_show');
Route::post('/remunerasi-staf-edit', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_staf_edit'])->name('remunerasi_staf_edit');
Route::get('/remunerasi-staf-edit-show', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_staf_edit_show'])->name('remunerasi_staf_edit_show');
Route::post('/remunerasi-medis-edit', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_medis_edit'])->name('remunerasi_medis_edit');
Route::get('/remunerasi-medis-edit-show', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_medis_edit_show'])->name('remunerasi_medis_edit_show');
Route::post('/remunerasi-komulatif', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_komulatif'])->name('remunerasi_komulatif');
Route::get('/remunerasi-export/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_export'])->name('remunerasi_export');
Route::get('/remunerasi-backup-export/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_backup_export'])->name('remunerasi_backup_export');
Route::get('/remunerasi-cetak', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_cetak'])->name('remunerasi_cetak');

Route::post('/remunerasi-penyesuaian-non-perawat', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_non_perawat'])->name('remunerasi_penyesuaian_non_perawat');
Route::post('/remunerasi-penyesuaian-staf', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_staf'])->name('remunerasi_penyesuaian_staf');
Route::post('/remunerasi-penyesuaian-operator', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_operator'])->name('remunerasi_penyesuaian_operator');
Route::post('/remunerasi-penyesuaian-spesialis', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_spesialis'])->name('remunerasi_penyesuaian_spesialis');
Route::post('/remunerasi-penyesuaian-umum', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_umum'])->name('remunerasi_penyesuaian_umum');
Route::post('/remunerasi-penyesuaian-perawat', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_perawat'])->name('remunerasi_penyesuaian_perawat');
Route::post('/remunerasi-penyesuaian-admin', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_admin'])->name('remunerasi_penyesuaian_admin');
Route::post('/remunerasi-penyesuaian-interensif', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_penyesuaian_interensif'])->name('remunerasi_penyesuaian_interensif');

Route::get('/remunerasi-rincian-export/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_rincian_export'])->name('remunerasi_rincian_export');

Route::get('/remunerasi-jaspel/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_cetak_jaspel'])->name('remunerasi_cetak_jaspel');
Route::get('/remunerasi-export-jaspel/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_export_jaspel'])->name('remunerasi_export_jaspel');
Route::get('/remunerasi-kwitansi/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_cetak_kwitansi'])->name('remunerasi_cetak_kwitansi');
Route::get('/remunerasi-kwitansi-export/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_kwitansi_export'])->name('remunerasi_kwitansi_export');

Route::get('/remunerasi-data', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_data'])->name('remunerasi_data');
Route::get('/remunerasi-data/{id}', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_data_hapus'])->name('remunerasi_data_hapus');
Route::get('/remunerasi-data-detil', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_data_detil'])->name('remunerasi_data_detil');
Route::get('/remunerasi-ori', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_ori'])->name('remunerasi_ori');
Route::get('/remunerasi-ori-detil', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_ori_detil'])->name('remunerasi_ori_detil');

Route::get('/remunerasi-olah', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_olah'])->name('remunerasi_olah');
Route::get('/remunerasi-olah-data', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_olah_data'])->name('remunerasi_olah_data');
Route::get('/remunerasi-kembali/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_olah_kembali'])->name('remunerasi_olah_kembali');
Route::get('/remunerasi-olah-cetak/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_olah_cetak'])->name('remunerasi_olah_cetak');
Route::get('/remunerasi-olah-export/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_olah_export'])->name('remunerasi_olah_export');
Route::get('/remunerasi-spj', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_olah_spj'])->name('remunerasi_olah_spj');
Route::get('/remunerasi-spj-data', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_spj_data'])->name('remunerasi_spj_data');
Route::get('/remunerasi-olah-data/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_olah_ok'])->name('remunerasi_olah_ok');

Route::post('/remunerasi-catatan', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_catatan'])->name('remunerasi_catatan');

Route::get('/remunerasi-verifikasi', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_verif'])->name('remunerasi_verif');

Route::get('/remunerasi-verifikasi-keuangan', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_keuangan'])->name('remunerasi_keuangan');
Route::get('/remunerasi-verifikasi-keuangan/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_keuangan_ok'])->name('remunerasi_keuangan_ok');

Route::get('/remunerasi-verifikasi-kepegawaian', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_kepegawaian'])->name('remunerasi_kepegawaian');
Route::get('/remunerasi-verifikasi-kepegawaian/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_kepegawaian_ok'])->name('remunerasi_kepegawaian_ok');

Route::get('/remunerasi-verifikasi/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_verifikasi'])->name('remunerasi_verifikasi');
Route::get('/remunerasi-tolak/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_tolak'])->name('remunerasi_tolak');
Route::get('/remunerasi-arsip/{id}', [App\Http\Controllers\RemunVerifController::class, 'remunerasi_arsip'])->name('remunerasi_arsip');

Route::get('/remunerasi-backup', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_backup'])->name('remunerasi_backup');
Route::get('/remunerasi-backup-detil', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_backup_detil'])->name('remunerasi_backup_detil');
Route::get('/remunerasi-backup-cetak', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_backup_cetak'])->name('remunerasi_backup_cetak');

Route::get('/remunerasi-original', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_original'])->name('remunerasi_original');
Route::get('/remunerasi-original-detil', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_original_detil'])->name('remunerasi_original_detil');

Route::get('/remunerasi-rincian', [App\Http\Controllers\RemunerasiController::class, 'remunerasi_rincian'])->name('remunerasi_rincian');

Route::get('/jasa-remun', [App\Http\Controllers\RemunerasiController::class, 'jasa_remun'])->name('jasa_remun');
Route::get('/jasa-remun/{id}', [App\Http\Controllers\RemunerasiController::class, 'jasa_remun_rincian'])->name('jasa_remun_rincian');
Route::get('/jasa-remun-cetak/{id}', [App\Http\Controllers\RemunerasiController::class, 'jasa_remun_cetak'])->name('jasa_remun_cetak');

Route::get('/pasien-ruang', [App\Http\Controllers\PasienController::class, 'pasien_ruang'])->name('pasien_ruang');
Route::get('/pasien-ruang-mobile', [App\Http\Controllers\PasienController::class, 'pasien_ruang_mobile'])->name('pasien_ruang_mobile');
Route::get('/pasien-ruang-data', [App\Http\Controllers\PasienController::class, 'pasien_ruang_data'])->name('pasien_ruang_data');
Route::get('/pasien-ruang-data-cetak', [App\Http\Controllers\PasienController::class, 'pasien_ruang_data_cetak'])->name('pasien_ruang_data_cetak');
Route::get('/pasien-ruang/{id}', [App\Http\Controllers\PasienController::class, 'pasien_ruang_detil'])->name('pasien_ruang_detil');
Route::get('/pasien-layanan-hapus/{id}', [App\Http\Controllers\PasienController::class, 'pasien_layanan_hapus'])->name('pasien_layanan_hapus');
Route::post('/pasien-baru', [App\Http\Controllers\PasienController::class, 'pasien_baru'])->name('pasien_baru');
Route::get('/pasien-baru', [App\Http\Controllers\PasienController::class, 'pasien_baru_form'])->name('pasien_baru_form');
Route::post('/pasien-edit', [App\Http\Controllers\PasienController::class, 'pasien_edit'])->name('pasien_edit');
Route::get('/pasien-pulang/{id}', [App\Http\Controllers\PasienController::class, 'pasien_pulang'])->name('pasien_pulang');
Route::get('/pasien-batal-pulang/{id}', [App\Http\Controllers\PasienController::class, 'pasien_batal_pulang'])->name('pasien_batal_pulang');
Route::get('/pasien-batal/{id}', [App\Http\Controllers\PasienController::class, 'pasien_batal'])->name('pasien_batal');
Route::post('/pasien-dpjp_baru', [App\Http\Controllers\PasienController::class, 'pasien_dpjp_baru'])->name('pasien_dpjp_baru');
Route::post('/pasien-pindah', [App\Http\Controllers\PasienController::class, 'pasien_pindah'])->name('pasien_pindah');
Route::post('/pasien-layanan', [App\Http\Controllers\PasienController::class, 'pasien_layanan'])->name('pasien_layanan');
Route::post('/pasien-layanan-multi', [App\Http\Controllers\PasienController::class, 'pasien_layanan_multi'])->name('pasien_layanan_multi');
Route::post('/pasien-layanan-multi-lain', [App\Http\Controllers\PasienController::class, 'pasien_layanan_multi_lain'])->name('pasien_layanan_multi_lain');
Route::post('/pasien-layanan-apotik', [App\Http\Controllers\PasienController::class, 'pasien_layanan_apotik'])->name('pasien_layanan_apotik');
Route::post('/pasien-ganti-dpjp', [App\Http\Controllers\PasienController::class, 'pasien_ganti_dpjp'])->name('pasien_ganti_dpjp');
Route::post('/pasien-ubah-status', [App\Http\Controllers\PasienController::class, 'pasien_ubah_status'])->name('pasien_ubah_status');
Route::post('/pasien-edit-ruang', [App\Http\Controllers\PasienController::class, 'pasien_edit_ruang'])->name('pasien_edit_ruang');

Route::get('/pasien-operasi', [App\Http\Controllers\PasienController::class, 'pasien_operasi'])->name('pasien_operasi');
Route::get('/pasien-operasi-mobile', [App\Http\Controllers\PasienController::class, 'pasien_operasi_mobile'])->name('pasien_operasi_mobile');
Route::get('/pasien-operasi/{id}', [App\Http\Controllers\PasienController::class, 'pasien_operasi_detil'])->name('pasien_operasi_detil');
Route::post('/pasien-operasi', [App\Http\Controllers\PasienController::class, 'pasien_layanan_operasi'])->name('pasien_layanan_operasi');

Route::get('/cek-pendamping', [App\Http\Controllers\PasienController::class, 'cek_pendamping'])->name('cek_pendamping');
Route::get('/cek-anastesi', [App\Http\Controllers\PasienController::class, 'cek_anastesi'])->name('cek_anastesi');

Route::get('/layanan-pasien', [App\Http\Controllers\PasienController::class, 'pasien_laborat'])->name('pasien_laborat');
Route::get('/layanan-pasien-mobile', [App\Http\Controllers\PasienController::class, 'pasien_laborat_mobile'])->name('pasien_laborat_mobile');
Route::get('/layanan-pasien/{id}', [App\Http\Controllers\PasienController::class, 'pasien_laborat_detil'])->name('pasien_laborat_detil');
Route::post('/layanan-pasien', [App\Http\Controllers\PasienController::class, 'pasien_layanan_laborat'])->name('pasien_layanan_laborat');
Route::post('/layanan-pasien-luar', [App\Http\Controllers\PasienController::class, 'pasien_luar_laborat'])->name('pasien_luar_laborat');
Route::get('/cek-tanggung', [App\Http\Controllers\PasienController::class, 'cek_tanggung'])->name('cek_tanggung');
Route::get('/layanan-pasien-transaksi', [App\Http\Controllers\PasienController::class, 'pasien_laborat_transaksi'])->name('pasien_laborat_transaksi');

Route::get('/pasien-apotik', [App\Http\Controllers\PasienController::class, 'pasien_apotik'])->name('pasien_apotik');
Route::get('/pasien-apotik-mobile', [App\Http\Controllers\PasienController::class, 'pasien_apotik_mobile'])->name('pasien_apotik_mobile');
Route::get('/pasien-apotik/{id}', [App\Http\Controllers\PasienController::class, 'pasien_apotik_detil'])->name('pasien_apotik_detil');
Route::post('/pasien-apotik-non', [App\Http\Controllers\PasienController::class, 'pasien_apotik_non'])->name('pasien_apotik_non');
Route::get('/pasien-apotik-transaksi', [App\Http\Controllers\PasienController::class, 'pasien_apotik_transaksi'])->name('pasien_apotik_transaksi');
Route::get('/pasien-jenasah-transaksi', [App\Http\Controllers\PasienController::class, 'pasien_jenasah_transaksi'])->name('pasien_jenasah_transaksi');
Route::get('/pasien-operasi-transaksi', [App\Http\Controllers\PasienController::class, 'pasien_operasi_transaksi'])->name('pasien_operasi_transaksi');

Route::get('/pasien-gizi', [App\Http\Controllers\PasienController::class, 'pasien_gizi'])->name('pasien_gizi');
Route::get('/pasien-gizi-mobile', [App\Http\Controllers\PasienController::class, 'pasien_gizi_mobile'])->name('pasien_gizi_mobile');
Route::get('/pasien-gizi/{id}', [App\Http\Controllers\PasienController::class, 'pasien_gizi_detil'])->name('pasien_gizi_detil');
Route::post('/pasien-gizi', [App\Http\Controllers\PasienController::class, 'pasien_gizi_layanan'])->name('pasien_gizi_layanan');
Route::get('/pasien-gizi-transaksi', [App\Http\Controllers\PasienController::class, 'pasien_gizi_transaksi'])->name('pasien_gizi_transaksi');

Route::get('/download', [App\Http\Controllers\HomeController::class, 'download'])->name('download');

Route::get('/pasien-upp', [App\Http\Controllers\PasienController::class, 'pasien_upp'])->name('pasien_upp');
Route::get('/pasien-upp-data', [App\Http\Controllers\PasienController::class, 'pasien_upp_data'])->name('pasien_upp_data');
Route::get('/pasien-upp-data/{id}', [App\Http\Controllers\PasienController::class, 'pasien_upp_data_rincian'])->name('pasien_upp_data_rincian');
Route::get('/pasien-upp/{id}', [App\Http\Controllers\PasienController::class, 'pasien_upp_verifikasi'])->name('pasien_upp_verifikasi');
Route::get('/pasien-upp-revisi/{id}', [App\Http\Controllers\PasienController::class, 'pasien_upp_revisi'])->name('pasien_upp_revisi');

Route::get('/mobile-pengaturan', [App\Http\Controllers\MobileController::class, 'pengaturan'])->name('mobile_pengaturan');
Route::get('/mobile-karyawan', [App\Http\Controllers\MobileController::class, 'karyawan'])->name('mobile_karyawan');
Route::get('/mobile-layanan', [App\Http\Controllers\MobileController::class, 'layanan'])->name('mobile_layanan');
Route::get('/mobile-bpjs', [App\Http\Controllers\MobileController::class, 'bpjs'])->name('mobile_bpjs');
Route::get('/mobile-remunerasi', [App\Http\Controllers\MobileController::class, 'remunerasi'])->name('mobile_remunerasi');
Route::get('/mobile-pasien', [App\Http\Controllers\MobileController::class, 'pasien'])->name('mobile_pasien');
Route::get('/informasi', [App\Http\Controllers\MobileController::class, 'informasi'])->name('mobile_informasi');

Route::get('/statistik-pasien', [App\Http\Controllers\PasienController::class, 'pasien_statistik'])->name('pasien_statistik');

Route::get('/jenis-pasien', [App\Http\Controllers\LayananController::class, 'jenis_pasien'])->name('jenis_pasien');
Route::post('/jenis-pasien-baru', [App\Http\Controllers\LayananController::class, 'jenis_pasien_baru'])->name('jenis_pasien_baru');
Route::get('/jenis-pasien-edit-show', [App\Http\Controllers\LayananController::class, 'jenis_pasien_edit_show'])->name('jenis_pasien_edit_show');
Route::post('/jenis-pasien-edit', [App\Http\Controllers\LayananController::class, 'jenis_pasien_edit'])->name('jenis_pasien_edit');
Route::get('/jenis-pasien/{id}', [App\Http\Controllers\LayananController::class, 'jenis_pasien_hapus'])->name('jenis_pasien_hapus');
Route::get('/jenis-pasien-aktifasi/{id}', [App\Http\Controllers\LayananController::class, 'jenis_pasien_aktifasi'])->name('jenis_pasien_aktifasi');

Route::get('/data-pasien-perawatan', [App\Http\Controllers\PasienController::class, 'pasien_perawatan_data'])->name('pasien_perawatan_data');
Route::get('/data-pasien-perawatan/{id}', [App\Http\Controllers\PasienController::class, 'pasien_perawatan_data_detil'])->name('pasien_perawatan_data_detil');

Route::get('/data-pasien-keluar', [App\Http\Controllers\PasienController::class, 'pasien_keluar_data'])->name('pasien_keluar_data');
Route::get('/data-pasien-layanan', [App\Http\Controllers\PasienController::class, 'pasien_layanan_data'])->name('pasien_layanan_data');

Route::get('/pengumuman', [App\Http\Controllers\PengumumanController::class, 'pengumuman'])->name('pengumuman');
Route::get('/pengumuman/{id}', [App\Http\Controllers\PengumumanController::class, 'pengumuman_detil'])->name('pengumuman_detil');
Route::get('/pengumuman-hapus/{id}', [App\Http\Controllers\PengumumanController::class, 'pengumuman_hapus'])->name('pengumuman_hapus');

Route::get('/pengumuman-baru', [App\Http\Controllers\PengumumanController::class, 'pengumuman_baru'])->name('pengumuman_baru');
Route::post('/pengumuman-baru', [App\Http\Controllers\PengumumanController::class, 'pengumuman_baru_simpan'])->name('pengumuman_baru_simpan');
Route::get('/pengumuman-edit/{id}', [App\Http\Controllers\PengumumanController::class, 'pengumuman_edit'])->name('pengumuman_edit');
Route::post('/pengumuman-edit', [App\Http\Controllers\PengumumanController::class, 'pengumuman_edit_simpan'])->name('pengumuman_edit_simpan');

Route::get('/pengumuman-rsud', [App\Http\Controllers\PengumumanController::class, 'pengumuman_user'])->name('pengumuman_user');
Route::get('/pengumuman-rsud/{id}', [App\Http\Controllers\PengumumanController::class, 'pengumuman_detil'])->name('pengumuman_detil');

Route::get('/absensi', [App\Http\Controllers\HomeController::class, 'absensi'])->name('absensi');
Route::get('/absensi-show', [App\Http\Controllers\HomeController::class, 'absensi_simpan_show'])->name('absensi_simpan_show');
Route::post('/absensi', [App\Http\Controllers\HomeController::class, 'absensi_simpan'])->name('absensi_simpan');
Route::post('/absensi-baru', [App\Http\Controllers\HomeController::class, 'absensi_baru'])->name('absensi_baru');

Route::get('/karyawan-histori-all', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_all'])->name('karyawan_histori_all');
Route::post('/karyawan-histori-all', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_all_update'])->name('karyawan_histori_all_update');
Route::post('/karyawan-histori-all-collect', [App\Http\Controllers\KaryawanController::class, 'karyawan_histori_all_kolektif'])->name('karyawan_histori_all_kolektif');