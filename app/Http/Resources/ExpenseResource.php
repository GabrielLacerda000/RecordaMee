<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'payment_date' => $this->payment_date,
            'isPaid' => $this->isPaid,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'status' => new StatusResource($this->whenLoaded('status')),
            'recurrence' => new RecurrenceResource($this->whenLoaded('recurrence')),
            'parent' => new ExpenseResource($this->whenLoaded('parent')),
        ];
    }
}
