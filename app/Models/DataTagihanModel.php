<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTagihanModel extends Model
{
    use HasFactory;

    protected $table = 'data_tagihan_pasien';

    protected $fillable = [
        'no_tagihan',
        'pasien_id',
        'perawatan_id',
        'jenis_diskon',
        'diskon',
        'total_tagihan',
        'total_diskon',
        'total_bayar',
        'sisa_tagihan',
        'status',
        'is_use_asuransi',
        'asuransi_pasien_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->no_tagihan = (new ConfSequenceNumberModel)->getSequenceNumber('tagihan');
        });
    }

    public function simpanDataAwal($data)
    {
        $this->pasien_id = $data['pasien_id'];
        $this->perawatan_id = $data['perawatan_id'];
        $this->status = 'draft';
        $this->is_use_asuransi = $data['is_use_asuransi'];
        $this->asuransi_pasien_id = $data['asuransi_pasien_id'] ?? null;
        $this->save();
        return $this->id;
    }
}
