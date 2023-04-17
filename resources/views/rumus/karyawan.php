->selectRaw('users.id,
                                    users.foto,
                                    users.nama,
                                    users.nip,
                                    users.username,
                                    users.jabatan,
                                    users.golongan,

                                    DATE_FORMAT(users.mulai_kerja,"%d %b %Y") as mulai_kerja,
                                    DATE_FORMAT(users.keluar,"%d %b %Y") as keluar,

                                    IF(users.keluar IS NULL,
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW()), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,NOW()) % 12, " Bln."),
                                    CONCAT(TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar), " Th. ",
                                    TIMESTAMPDIFF(MONTH,users.mulai_kerja,users.keluar) % 12, " Bln.")) as masa_kerja,

                                    users.gapok,

                                    ROUND((users.koreksi/(SELECT parameter.koreksi FROM parameter)),2) as indeks_dasar,

                                    IF(users.keluar IS NULL,
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                    (SELECT dt_indek.indeks
                                     FROM dt_indek
                                     WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                     AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) as indeks_kerja,

                                    ((((ROUND((users.koreksi / (SELECT parameter.koreksi FROM parameter)),2) * users.dasar_bobot) + 

                                    (IF(users.keluar IS NULL,
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,NOW())),
                                     (SELECT dt_indek.indeks
                                      FROM dt_indek
                                      WHERE dt_indek.dari <= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar)
                                      AND dt_indek.sampai >= TIMESTAMPDIFF(YEAR,users.mulai_kerja,users.keluar))) * users.masa_kerja_bobot)) / 2) +

                                    (users.pend_nilai * users.pend_bobot) + 
                                    (users.diklat_nilai * users.diklat_bobot) + 
                                    (users.resiko_nilai * users.resiko_bobot) + 
                                    (users.gawat_nilai * users.gawat_bobot) + 
                                    (users.jab_nilai * users.jab_bobot) + 
                                    (users.panitia_nilai * users.panitia_bobot) + 
                                    (users.perform_nilai * users.perform_bobot)) as skore,
   
                                    users.pajak,
                                    users.tpp,
                                    users.npwp,
                                    users_tenaga_bagian.bagian,
                                    users_tenaga_bagian.urut,
                                    users_tenaga.tenaga,
                                    dt_ruang.ruang,
                                    users_status.status,
                                    users_akses.akses')