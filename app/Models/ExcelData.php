<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelData extends Model
{
    use HasFactory;
    protected $table = 'excel_data';
    protected $fillable = ['excel_1', 'excel_2', 'merged_file'];

    /**
     * Pastikan data yang disimpan hanya berupa string (path file).
     */
    public function setExcel1Attribute($value)
    {
        $this->attributes['excel_1'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setExcel2Attribute($value)
    {
        $this->attributes['excel_2'] = is_array($value) ? json_encode($value) : $value;
    }
}
