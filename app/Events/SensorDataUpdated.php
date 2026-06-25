<?php

namespace App\Events;

use App\Models\SensorData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorDataUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sensorData;

    /**
     * Create a new event instance.
     */
    public function __construct(SensorData $sensorData)
    {
        $this->sensorData = $sensorData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('sensor-channel'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'SensorDataUpdated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->sensorData->id,
            'gas_value' => $this->sensorData->gas_value,
            'gas_ppm' => $this->sensorData->gas_ppm,
            'status' => $this->sensorData->status,
            'apar_aktif' => $this->sensorData->apar_aktif,
            'buzzer_aktif' => $this->sensorData->buzzer_aktif,
            'created_at' => $this->sensorData->created_at ? $this->sensorData->created_at->toDateTimeString() : null,
        ];
    }
}
