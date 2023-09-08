<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataAsuransiPasienModel extends Model
{
    use HasFactory;

    protected $table = 'data_asuransi_pasien';

    protected $fillable = [
        'slug_number',
        'pasien_id',
        'asuransi_id',
        'tipe_asuransi_id',
        'nomor_peserta',
        'status',
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
            $model->slug_number = (new ConfSequenceNumberModel)->getSequenceNumber('asuransi_pasien');
        });
    }

    public function tambah_asuransi_pasien($data)
    {
        $this->pasien_id = $data['pasien_id'];
        $this->asuransi_id = $data['asuransi_id'];
        $this->tipe_asuransi_id = $data['tipe_asuransi_id'] ?? null;
        $this->nomor_peserta = $data['nomor_peserta'];
        $this->status = $data['status'] ?? 'aktif'; // 'aktif' or 'tidak_aktif
        $this->save();
        if($data['tipe_asuransi_id'] != null){
            $tipe_asuransi = DB::table('data_asuransi_tipe')
                ->where('id', $data['tipe_asuransi_id'])
                ->first();
            $tanggungan = json_decode($tipe_asuransi->tanggungan);
            foreach ($tanggungan as $tgn) {
                DB::table('data_asuransi_pasien_tanggungan')->insert([
                    'asuransi_pasien_id' => $this->id,
                    'jenis_tanggungan' => $tgn->value ?? 'all',
                    'nama_tanggungan' => $tgn->value == 'all' ? 'Semua Layanan' : ucfirst($tgn->value),
                    'is_limit' => false,
                    'limit' => 0,
                    'sisa_limit' => 0,
                    'tanggal_terakhir_penggunaan' => null,
                ]);
            }
        } else {
            DB::table('data_asuransi_pasien_tanggungan')->insert([
                'asuransi_pasien_id' => $this->id,
                'jenis_tanggungan' => 'all',
                'nama_tanggungan' => 'Semua Layanan',
                'is_limit' => false,
                'limit' => 0,
                'sisa_limit' => 0,
                'tanggal_terakhir_penggunaan' => null,
            ]);
        }
        return $this->id;
    }
}
