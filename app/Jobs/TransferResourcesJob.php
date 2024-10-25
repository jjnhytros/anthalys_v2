<?php

namespace App\Jobs;

use App\Models\City\Message;
use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TransferResourcesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fromDistrict;
    protected $toDistrict;
    protected $resourceType;
    protected $amount;

    public function __construct(District $fromDistrict, District $toDistrict, string $resourceType, float $amount)
    {
        $this->fromDistrict = $fromDistrict;
        $this->toDistrict = $toDistrict;
        $this->resourceType = $resourceType;
        $this->amount = $amount;
    }

    public function handle()
    {
        // Verifica che ci siano risorse sufficienti nel distretto di partenza
        if ($this->fromDistrict->{$this->resourceType} >= $this->amount) {
            // Sottrai risorse dal distretto di partenza
            $this->fromDistrict->{$this->resourceType} -= $this->amount;
            $this->fromDistrict->save();

            // Aggiungi risorse al distretto di destinazione
            $this->toDistrict->{$this->resourceType} += $this->amount;
            $this->toDistrict->save();

            // Notifica il trasferimento
            Message::create([
                'sender_id' => 2, // ID del governo
                'recipient_id' => $this->toDistrict->manager_id,
                'subject' => 'Trasferimento Risorse',
                'body' => "Sono state trasferite {$this->amount} unità di {$this->resourceType} dal distretto {$this->fromDistrict->name} al distretto {$this->toDistrict->name}.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }
    }
}
