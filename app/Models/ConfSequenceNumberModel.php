<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfSequenceNumberModel extends Model
{
    use HasFactory;
    protected $table = 'conf_sequence_number';
    protected $fillable = [
        'name',
        'code',
        'prefix',
        'padding',
        'last_number',
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
        static::updating(function ($model) {
            // gmt+8
            $model->updated_at = date('Y-m-d H:i:s');
        });
    }
    public function getSequenceNumber($code)
    {
        $sequence_number = $this->where('code', $code)->first();
        if (!$sequence_number) {
            return null;
        }
        $date_now = date('Y-m-d H:i:s');
        if ($sequence_number->reset_number == 'year') {
            $year = date('Y', strtotime($date_now));
            $last_year = date('Y', strtotime($sequence_number->updated_at));
            if ($year != $last_year) {
                $sequence_number->last_number = 1;
            } else {
                $sequence_number->last_number = $sequence_number->last_number + 1;
            }
        } elseif ($sequence_number->reset_number == 'month') {
            $month = date('m', strtotime($date_now));
            $last_month = date('m', strtotime($sequence_number->updated_at));
            if ($month != $last_month) {
                $sequence_number->last_number = 1;
            } else {
                $sequence_number->last_number = $sequence_number->last_number + 1;
            }
        } elseif ($sequence_number->reset_number == 'day') {
            $day = date('d', strtotime($date_now));
            $last_day = date('d', strtotime($sequence_number->updated_at));
            if ($day != $last_day) {
                $sequence_number->last_number = 1;
            } else {
                $sequence_number->last_number = $sequence_number->last_number + 1;
            }
        } elseif ($sequence_number->reset_number == 'never') {
            $sequence_number->last_number = $sequence_number->last_number + 1;
        }
        $sequence_number->updated_at = $date_now;
        $sequence_number->save();
        $separator = strval($sequence_number->separator) != '' ? strval($sequence_number->separator) : '';
        $prefix = $sequence_number->prefix . $separator;
        if ($sequence_number->is_use_date == 1) {
            // comvert to string
            $date_format = (string) $sequence_number->date_format;
            $prefix .= date($date_format, strtotime($date_now)) . $separator;
        }
        return $prefix . str_pad($sequence_number->last_number, $sequence_number->padding, '0', STR_PAD_LEFT);
    }
}
