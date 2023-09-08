<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRekamMedisModel extends Model
{
    use HasFactory;

    protected $table = 'data_rekam_medis';

    protected $fillable = [
        'no_erm',
        'pasien_id',
        'keterangan',
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
            $model->no_erm = (new ConfSequenceNumberModel)->getSequenceNumber('emr');
        });
    }

    public function simpanDataAwal($data)
    {
        $this->pasien_id = $data['pasien_id'];
        $this->keterangan = $data['keterangan'];
        $this->save();
        return $this->id;
    }
}
