#!/usr/bin/env bash
#
# Perbaiki mismatch DB: DataTables "Base table or view not found: v_jadwal_nilai".
#
# View `v_jadwal_nilai` dipakai Nilai::data() (server-side DataTables "Daftar Kelas
# yang Diajar") tetapi tidak ada di dump pis_akademik.sql maupun di DB yang sudah
# berjalan. Script ini membuatnya (idempotent).
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

VIEW_NAME="v_jadwal_nilai"

# SQL untuk membuat view yang hilang.
# Kolom ekstra (id_tahun_akademik, semester, id_guru, hari, jam) wajib ada karena
# dipakai di klausa WHERE server-side DataTables (Nilai::data()).
VIEW_SQL=$(cat <<'SQL'
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
  tj.id_guru             AS id_guru
FROM tbl_jadwal tj
JOIN tbl_kelas tk            ON tk.kd_kelas      = tj.kd_kelas
JOIN tbl_jurusan tju         ON tju.kd_jurusan   = tj.kd_jurusan
JOIN tbl_tingkatan_kelas ttk ON ttk.kd_tingkatan = tj.kd_tingkatan
JOIN tbl_mapel tm            ON tm.kd_mapel      = tj.kd_mapel
JOIN tbl_ruangan tr          ON tr.kd_ruangan    = tj.kd_ruangan;
SQL
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
          "$DB_NAME" "$VIEW_NAME" | run_sql)
  [ "$count" = "1" ]
}

echo "==> Memeriksa view \`$VIEW_NAME\` di database \`$DB_NAME\`..."

if view_exists; then
  if [ "$FORCE" = "--force" ]; then
    printf "DROP VIEW IF EXISTS \`%s\`;\n%s" "$VIEW_NAME" "$VIEW_SQL" | run_sql >/dev/null
    echo "==> View \`$VIEW_NAME\` sudah dibuat ulang (--force)."
  else
    echo "==> View \`$VIEW_NAME\` sudah ada, tidak perlu apa-apa."
  fi
else
  # Referensi view bisa didefinisikan ulang: buat ulang lebih aman daripada create-if-not-exists
  # karena kolom/data lama (bila ada) tidak perlu dipertahankan merujuk struktur lama.
  printf "%s" "$VIEW_SQL" | run_sql >/dev/null
  echo "==> View \`$VIEW_NAME\` berhasil dibuat."
fi

# Verifikasi akhir
if view_exists; then
  echo "==> OK: view \`$VIEW_NAME\` tersedia di \`$DB_NAME\`."
else
  echo "==> GAGAL: view \`$VIEW_NAME\` masih tidak ada." >&2
  exit 1
fi