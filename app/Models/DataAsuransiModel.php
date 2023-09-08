<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAsuransiModel extends Model
{
    use HasFactory;

    protected $table = 'data_asuransi';

    protected $fillable = [
        'slug_number',
        'kode_asuransi',
        'nama_asuransi',
        'status',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->slug_number = (new ConfSequenceNumberModel)->getSequenceNumber('asuransi');
        });
    }

    public function tambah_asuransi($data)
    {
        $this->kode_asuransi = $data['kode_asuransi'];
        $this->nama_asuransi = $data['nama_asuransi'];
        $this->status = $data['status'] ?? 'tidak_aktif'; // 'aktif' or 'tidak_aktif
        $this->keterangan = $data['keterangan'] ?? '';
        $this->save();
        return $this->id;
    }
}
