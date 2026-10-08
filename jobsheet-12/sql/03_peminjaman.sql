-- Jobsheet 12: tabel peminjaman, menghubungkan buku, anggota, dan users
-- Jalankan: psql -d simpus_mini -f sql/03_peminjaman.sql

CREATE TABLE IF NOT EXISTS peminjaman (
    id SERIAL PRIMARY KEY,
    buku_id INTEGER NOT NULL REFERENCES buku(id),
    anggota_id INTEGER NOT NULL REFERENCES anggota(id),
    tanggal_pinjam DATE NOT NULL DEFAULT CURRENT_DATE,
    tanggal_jatuh_tempo DATE NOT NULL DEFAULT (CURRENT_DATE + 14),
    tanggal_kembali DATE,
    status VARCHAR(20) NOT NULL DEFAULT 'dipinjam'
);

-- Untuk database yang sudah punya tabel peminjaman (dari versi sebelumnya):
-- tambahkan kolom jatuh tempo, lalu isi data lama = tanggal_pinjam + 14 hari.
ALTER TABLE peminjaman ADD COLUMN IF NOT EXISTS tanggal_jatuh_tempo DATE;
UPDATE peminjaman SET tanggal_jatuh_tempo = tanggal_pinjam + 14 WHERE tanggal_jatuh_tempo IS NULL;
