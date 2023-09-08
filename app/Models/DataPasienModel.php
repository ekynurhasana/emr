<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConfSequenceNumberModel;

class DataPasienModel extends Model
{
    use HasFactory;

    protected $table = 'data_pasien';

    protected $fillable = [
        'nama_pasien',
        'jenis_kelamin',
        'gol_darah',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'no_ktp',
        'agama',
        'pekerjaan',
        'status_perkawinan',
        'nama_wali',
        'hubungan_dengan_wali',
        'hubungan_dengan_wali_lainnya',
        'jenis_kelamin_wali',
        'alamat_wali',
        'no_telepon_wali',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'tanggal_lahir' => 'date:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->slug_number = (new ConfSequenceNumberModel)->getSequenceNumber('pasien');
        });
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function tambah_pasien_pendaftaran($data)
    {
        $this->nama_pasien = $data['nama_pasien'];
        $this->no_ktp = $data['no_ktp'];
        $this->save();
        return $this->id;
    }
}
