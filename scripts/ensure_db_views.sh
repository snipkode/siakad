#!/usr/bin/env bash
#
# Perbaiki mismatch DB: DataTables "Base table or view not found: ...".
#
# View yang dibuat (idempotent):
#   v_jadwal_nilai   -> daftar jadwal lengkap utk Nilai::data() (sekolah)
#   v_krs_mahasiswa  -> ringkasan KRS/IP/IPK per mahasiswa (kampus)
#
# Penggunaan:
#   ./scripts/ensure_db_views.sh            # buat view jika belum ada
#   ./scripts/ensure_db_views.sh --force    # selalu recreate (DROP + CREATE)
#
# Environment (optional, sudah ada default untuk docker compose siakad):
#   DB_CONTAINER  nama container MySQL   (default: siakad-db)
#   DB_NAME       nama database          (default: pis_akademik)
#   DB_USER       user MySQL             (default: root)
#   DB_PASS       password MySQL         (default: root)
#   DB_HOST       host mysql saat tanpa docker (default: 127.0.0.1)
#
# Catatan: untuk memakai mysql klien lokal (non-docker), set DB_CONTAINER="".
#
set -euo pipefail

DB_CONTAINER="${DB_CONTAINER:-siakad-db}"
DB_NAME="${DB_NAME:-pis_akademik}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-root}"
DB_HOST="${DB_HOST:-127.0.0.1}"
FORCE="${1:-}"

# -- definisi view ----------------------------------------------------------
# Kolom ekstra (id_tahun_akademik, semester, id_guru, hari, jam, kd_mode) wajib
# ada karena dipakai di klausa WHERE server-side DataTables (Nilai::data()).
VIEW_JADWAL_NILAI=$(cat <<'SQL'
CREATE OR REPLACE VIEW `v_jadwal_nilai` AS
SELECT
  tj.id_jadwal           AS id_jadwal,
  tk.nama_kelas          AS nama_kelas,
  CONCAT(tju.nama_jurusan, ' ', ttk.nama_tingkatan) AS jurusan_tingkatan,
  tm.nama_mapel          AS nama_mapel,
  tj.hari                AS hari,
  tj.jam                 AS jam,
  tr.nama_ruangan        AS nama_ruangan,
  tj.id_tahun_akademik   AS id_tahun_akademik,
  tj.semester            AS semester,
  tj.id_guru             AS id_guru,
  tj.kd_mode             AS kd_mode
FROM tbl_jadwal tj
JOIN tbl_kelas tk            ON tk.kd_kelas      = tj.kd_kelas
JOIN tbl_jurusan tju         ON tju.kd_jurusan   = tj.kd_jurusan
JOIN tbl_tingkatan_kelas ttk ON ttk.kd_tingkatan = tj.kd_tingkatan
JOIN tbl_mapel tm            ON tm.kd_mapel      = tj.kd_mapel
JOIN tbl_ruangan tr          ON tr.kd_ruangan    = tj.kd_ruangan;
SQL
)

VIEW_KRS_MAHASISWA=$(cat <<'SQL'
CREATE OR REPLACE VIEW `v_krs_mahasiswa` AS
SELECT
  ts.nim              AS nim,
  ts.nama             AS nama,
  ts.gender           AS gender,
  tk.kd_kelas         AS kd_kelas,
  tk.nama_kelas       AS nama_kelas,
  tk.kd_prodi         AS kd_prodi,
  rp.nama             AS nama_prodi,
  tk.angkatan         AS angkatan,
  tk.kd_mode          AS kd_mode,
  ta.id_tahun_akademik AS id_tahun_akademik,
  COALESCE((SELECT SUM(tn.sks)
            FROM tbl_nilai tn
            JOIN tbl_jadwal tj ON tj.id_jadwal = tn.id_jadwal
            WHERE tn.nim = ts.nim AND tj.kd_mode = 'KAMPUS'
              AND tj.id_tahun_akademik = ta.id_tahun_akademik
              AND tj.semester = ta.semester), 0) AS sks_diambil,
  COALESCE((SELECT ROUND(SUM(tn.nilai * tn.sks) / NULLIF(SUM(CASE WHEN tn.nilai > 0 THEN tn.sks END), 0), 2)
            FROM tbl_nilai tn
            JOIN tbl_jadwal tj ON tj.id_jadwal = tn.id_jadwal
            WHERE tn.nim = ts.nim AND tj.kd_mode = 'KAMPUS'
              AND tj.id_tahun_akademik = ta.id_tahun_akademik
              AND tj.semester = ta.semester
              AND tn.nilai > 0), 0) AS ip,
  COALESCE((SELECT ROUND(SUM(tn.nilai * tn.sks) / NULLIF(SUM(CASE WHEN tn.nilai > 0 THEN tn.sks END), 0), 2)
            FROM tbl_nilai tn
            JOIN tbl_jadwal tj ON tj.id_jadwal = tn.id_jadwal
            WHERE tn.nim = ts.nim AND tj.kd_mode = 'KAMPUS'
              AND tn.nilai > 0), 0) AS ipk
FROM tbl_siswa ts
JOIN tbl_riwayat_kelas trk ON trk.nim = ts.nim
JOIN tbl_kelas tk ON tk.kd_kelas = trk.kd_kelas
LEFT JOIN tbl_referensi rp ON rp.kategori = 'PRODI' AND rp.kode = tk.kd_prodi
CROSS JOIN tbl_tahun_akademik ta
WHERE ta.is_aktif = 'Y' AND tk.kd_mode = 'KAMPUS';
SQL
)

declare -A VIEWS=(
  ["v_jadwal_nilai"]="$VIEW_JADWAL_NILAI"
  ["v_krs_mahasiswa"]="$VIEW_KRS_MAHASISWA"
)

run_sql() {
  # Pipe heredoc/SQL ke klien mysql: docker exec bila container tersedia,
  # kalau tidak gunakan mysql klien lokal.
  if [ -n "$DB_CONTAINER" ] && docker ps --format '{{.Names}}' 2>/dev/null | grep -qx "$DB_CONTAINER"; then
    docker exec -i "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASS" -N -B "$DB_NAME"
  else
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -N -B "$DB_NAME"
  fi
}

view_exists() {
  local count
  count=$(printf "SELECT COUNT(*) FROM information_schema.views \
                  WHERE table_schema = '%s' AND table_name = '%s';" \
          "$DB_NAME" "$1" | run_sql)
  [ "$count" = "1" ]
}

for v in "${!VIEWS[@]}"; do
  echo "==> Memeriksa view \`$v\` di database \`$DB_NAME\`..."
  if view_exists "$v"; then
    if [ "$FORCE" = "--force" ]; then
      printf "DROP VIEW IF EXISTS \`%s\`;\n%s" "$v" "${VIEWS[$v]}" | run_sql >/dev/null
      echo "==> View \`$v\` sudah dibuat ulang (--force)."
    else
      echo "==> View \`$v\` sudah ada, tidak perlu apa-apa."
    fi
  else
    printf "%s" "${VIEWS[$v]}" | run_sql >/dev/null
    echo "==> View \`$v\` berhasil dibuat."
  fi

  if view_exists "$v"; then
    echo "==> OK: view \`$v\` tersedia di \`$DB_NAME\`."
  else
    echo "==> GAGAL: view \`$v\` masih tidak ada." >&2
    exit 1
  fi
done