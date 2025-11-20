<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::query()
            ->where(function ($query) use ($request) {
                $query->where('sender_id', $request->user()->id)
                    ->orWhere('receiver_id', $request->user()->id);
            })
            ->with(['sender', 'receiver', 'job'])
            ->latest()
            ->paginate(20);

        return MessageResource::collection($messages);
    }

    public function store(MessageRequest $request): JsonResponse
    {
        $message = Message::create(array_merge(
            $request->validated(),
            ['sender_id' => $request->user()->id],
        ));

        return response()->json([
            'message' => new MessageResource($message->load(['sender', 'receiver', 'job'])),
            'status' => 'sent',
        ], 201);
    }
}

