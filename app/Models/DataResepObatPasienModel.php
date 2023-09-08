<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataResepObatPasienModel extends Model
{
    use HasFactory;

    protected $table = 'data_resep_obat_pasien';
    protected $fillable = [
        'no_resep',
        'pendaftaran_id',
        'pasien_id',
        'dokter_poli_id',
        'resep_dokter',
        'status',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->no_resep = (new ConfSequenceNumberModel)->getSequenceNumber('resep');
        });
    }

    public function simpanDataAwal($data)
    {
        $this->pendaftaran_id = $data['pendaftaran_id'];
        $this->pasien_id = $data['pasien_id'];
        $this->dokter_poli_id = $data['dokter_poli_id'];
        $this->resep_dokter = $data['resep_dokter'];
        $this->status = $data['status'];
        $this->keterangan = $data['keterangan'] ?? null;
        $this->save();
        return $this->id;
    }
}
