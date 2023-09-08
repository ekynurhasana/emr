<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConfSequenceNumberModel;

class DataPendaftarPerawatanModel extends Model
{
    use HasFactory;

    protected $table = 'data_pendaftar_perawatan';

    protected $fillable = [
        'no_pendaftaran',
        'status',
        'pasien_id',
        'poli_id',
        'dokter_poli_id',
        'dokter_id',
        'tanggal_pendaftaran',
        'tgl_periksa',
        'jadwal_dokter_id',
        'keluhan',
        'riwayat_penyakit',
        'tekanan_darah',
        'nadi',
        'berat_badan',
        'tinggi_badan',
        'suhu_badan',
        'pemeriksaan_fisik_lainnya',
        'no_antrian',
        'is_periksa_lanjutan',
        'rencana_jadwal_selanjutnya',
        'is_use_asuransi',
        'asuransi_pasien_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal_pendaftaran' => 'datetime:Y-m-d H:i:s',
        'rencana_jadwal_selanjutnya' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->no_pendaftaran = (new ConfSequenceNumberModel)->getSequenceNumber('reg');
        });
    }

    public function simpanDataAwal($data)
    {
        $this->pasien_id = $data['pasien_id'];
        $this->poli_id = $data['poli_id'];
        $this->dokter_poli_id = $data['dokter_poli_id'];
        $this->dokter_id = $data['dokter_id'];
        $this->tanggal_pendaftaran = date('Y-m-d H:i:s');
        $this->tgl_periksa = $data['tgl_periksa'];
        $this->jadwal_dokter_id = $data['jadwal_dokter_id'];
        $this->is_use_asuransi = $data['is_use_asuransi'];
        $this->asuransi_pasien_id = $data['asuransi_pasien_id'] ?? null;
        $this->save();
        return $this->id;
    }
}
