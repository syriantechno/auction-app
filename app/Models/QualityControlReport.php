<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityControlReport extends Model
{
    protected $fillable = [
        'stock_entry_id', 'inspection_report_id', 'qc_by', 'status',
        'paint_verified',        'paint_notes',
        'engine_verified',       'engine_notes',
        'transmission_verified', 'transmission_notes',
        'interior_verified',     'interior_notes',
        'tires_verified',        'tires_notes',
        'body_verified',         'body_notes',
        'documents_verified',    'documents_notes',
        'keys_count_verified',   'additional_notes',
        'approved_at',
    ];

    protected $casts = [
        'paint_verified'        => 'boolean',
        'engine_verified'       => 'boolean',
        'transmission_verified' => 'boolean',
        'interior_verified'     => 'boolean',
        'tires_verified'        => 'boolean',
        'body_verified'         => 'boolean',
        'documents_verified'    => 'boolean',
        'keys_count_verified'   => 'boolean',
        'approved_at'           => 'datetime',
    ];

    public function stockEntry()       { return $this->belongsTo(StockEntry::class); }
    public function inspectionReport() { return $this->belongsTo(InspectionReport::class); }
    public function qcBy()             { return $this->belongsTo(User::class, 'qc_by'); }

    // All sections verified?
    public function isFullyVerified(): bool
    {
        return $this->paint_verified
            && $this->engine_verified
            && $this->transmission_verified
            && $this->interior_verified
            && $this->tires_verified
            && $this->body_verified
            && $this->documents_verified;
    }
}
