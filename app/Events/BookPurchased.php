<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookPurchased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchaseData;

    /**
     * Create a new event instance.
     *
     * @param array $purchaseData Информация о покупке (книга, пользователь, количество)
     */
    public function __construct(array $purchaseData)
    {
        $this->purchaseData = $purchaseData;

        // Логируем создание события для отладки
        Log::info('Событие BookPurchased создано', ['data' => $purchaseData]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('purchases'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'book.purchased';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $data = [
            'purchase_id' => $this->purchaseData['id'] ?? null,
            'book_id' => $this->purchaseData['book_id'] ?? null,
            'book_title' => $this->purchaseData['book_title'] ?? null,
            'user_id' => $this->purchaseData['user_id'] ?? null,
            'user_name' => $this->purchaseData['user_name'] ?? null,
            'quantity' => $this->purchaseData['quantity'] ?? 1,
            'price' => $this->purchaseData['price'] ?? null,
            'total_price' => $this->purchaseData['total_price'] ?? null,
            'purchased_at' => $this->purchaseData['purchased_at'] ?? now()->format('Y-m-d H:i:s'),
        ];
        return $data;
    }
}
