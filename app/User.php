<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'foto',
        'nama',
        'nip',
        'alamat',
        'dusun',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'temp_lahir',
        'tgl_lahir',
        'id_kelamin',
        'mulai_kerja',
        'id_tenaga_bagian',
        'telp',
        'hp',
        'email',
        'id_status',
        'id_ruang',
        'id_ruang_1',
        'pendidikan',
        'gapok',
        'koreksi',
        'pend_nilai',
        'pend_bobot',
        'diklat_nilai',
        'diklat_bobot',
        'temp_tugas',
        'resiko_nilai',
        'resiko_bobot',
        'gawat_nilai',
        'gawat_bobot',
        'jabatan',
        'jab_nilai',
        'jab_bobot',
        'panitia_nilai',
        'panitia_bobot',
        'perform_nilai',
        'perform_bobot',
        'masa_kerja_bobot',
        'skore',
        'pajak',
        'tpp',
        'jp_perawat',
        'jp_admin',
        'pos_remun',
        'direksi',
        'staf',
        'insentif_perawat',
        'apoteker',
        'ass_apoteker',
        'admin_farmasi',
        'pen_anastesi',
        'per_asisten_1',
        'per_asisten_2',
        'instrumen',
        'sirkuler',
        'per_pendamping_1',
        'per_pendamping_2',
        'medis',
        'golongan',
        'npwp',
        'rekening',
        'bank',
        'tgl_hapus',
        'hapus',
        'id_akses',
        'username',
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
