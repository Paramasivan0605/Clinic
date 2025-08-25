<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Appointment extends Model
{
    protected $fillable = [
        'client_name','client_email','client_phone','service',
        'date','start_time','end_time','status','notes'
    ];

    protected static function booted()
{
    static::creating(function ($appointment) {
        // AP-20250825-0001
       $appointment->appointment_no = 'AP-' . now()->format('Y') . '-' . str_pad((Appointment::max('id') + 1), 4, '0', STR_PAD_LEFT);
    });
}


    // Simple filter scopes
    public function scopeFilter(Builder $q, array $filters): Builder
    {
        return $q
            ->when($filters['search'] ?? null, function ($q, $s) {
                $q->where(function($qq) use ($s) {
                     $qq->where('appointment_no', 'like', "%$s%")
                        ->orwhere('client_name','like',"%$s%")
                       ->orWhere('client_email','like',"%$s%")
                       ->orWhere('client_phone','like',"%$s%")
                       ->orWhere('service','like',"%$s%");
                });
            })
            ->when($filters['status'] ?? null, fn($q,$v) => $q->where('status',$v))
            ->when($filters['service'] ?? null, fn($q,$v) => $q->where('service',$v))
            ->when($filters['from'] ?? null, fn($q,$v) => $q->whereDate('date','>=',$v))
            ->when($filters['to'] ?? null, fn($q,$v) => $q->whereDate('date','<=',$v));
    }

    public function toFullCalendarEvent(): array
    {
        $start = $this->start_time ? "{$this->date} {$this->start_time}" : "{$this->date} 09:00:00";
        $end   = $this->end_time   ? "{$this->date} {$this->end_time}"   : "{$this->date} 09:30:00";

        return [
            'id' => $this->id,
            'title' => $this->client_name . ' • ' . ($this->service ?: 'Appointment'),
            'start' => $start,
            'end' => $end,
            'color' => match($this->status) {
                'approved' => '#16a34a',
                'cancelled'=> '#ef4444',
                default    => '#0ea5e9',
            }
        ];
    }
}
