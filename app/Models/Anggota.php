<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Anggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'anggota';

    /**
     * Kolom yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_anggota',
        'nama',
        'email',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'tanggal_daftar',
        'status',
    ];

    /**
     * Tipe casting untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir'  => 'date',
        'tanggal_daftar' => 'date',
    ];

    // ========================================================
    // ACCESSOR (dari Praktikum 4)
    // ========================================================

    /**
     * Accessor: umur — menghitung umur dari tanggal_lahir
     */
    public function getUmurAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Accessor: lama_anggota — selisih hari sejak tanggal_daftar
     */
    public function getLamaAnggotaAttribute(): int
    {
        return Carbon::parse($this->tanggal_daftar)->diffInDays(now());
    }

    // ========================================================
    // ACCESSOR TUGAS 2
    // ========================================================

    /**
     * Accessor: status_badge
     * Mengembalikan HTML badge Bootstrap berdasarkan status:
     *   - Aktif    → badge hijau
     *   - Nonaktif → badge abu-abu
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status === 'Aktif') {
            return '<span class="badge bg-success">Aktif</span>';
        }

        return '<span class="badge bg-secondary">Nonaktif</span>';
    }

    /**
     * Accessor: kategori_usia
     * Mengembalikan kategori usia berdasarkan umur:
     *   - Umur < 20  → "Remaja"
     *   - Umur 20–50 → "Dewasa"
     *   - Umur > 50  → "Senior"
     */
    public function getKategoriUsiaAttribute(): string
    {
        $umur = $this->umur;

        if ($umur < 20) {
            return 'Remaja';
        } elseif ($umur <= 50) {
            return 'Dewasa';
        } else {
            return 'Senior';
        }
    }

    // ========================================================
    // SCOPE (dari Praktikum 4)
    // ========================================================

    /**
     * Scope: aktif — filter anggota dengan status Aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Scope: jenisKelamin — filter berdasarkan jenis kelamin
     */
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    // ========================================================
    // SCOPE TUGAS 2
    // ========================================================

    /**
     * Scope: jenisKelamin (sudah ada di atas, dipakai ulang di tugas)
     * Filter berdasarkan jenis kelamin: 'Laki-laki' atau 'Perempuan'
     */

    /**
     * Scope: terdaftarBulanIni — anggota yang tanggal_daftar-nya di bulan & tahun ini
     */
    public function scopeTerdaftarBulanIni($query)
    {
        return $query->whereMonth('tanggal_daftar', now()->month)
                     ->whereYear('tanggal_daftar', now()->year);
    }
}
