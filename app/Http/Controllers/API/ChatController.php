<?php

namespace App\Http\Controllers\API;

use App\Components\Classes\Chat\Chat;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatRoomResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function chats()
    {
        $pet = auth()->user()->pet;
        $responseData = ChatRoomResource::collection($pet->chatRooms)->toArray(request());

        return response()->json($responseData);
    }

    public function create(Request $request)
    {
        $friend = $request->get('friend_id');
        $chat = Chat::create($friend);

        return response()->json(['room_id' => $chat->getRoom()->id]);
    }

    public function roomMessages(Request $request, $room)
    {
        $chat = Chat::get((int)$room);

        if($request->input('page') == 1)
            $chat->updateRead();

        return response()->json($chat->getMessages());
    }

    public function send(Request $request, $room)
    {
        $chat = Chat::get((int)$room);

        $message = $request->get('message');
        $type = $request->get('type');

        $chatMessage = $chat->send($message, $type);

        return response()->json(new ChatMessageResource($chatMessage));
    }

    public function destroy($room)
    {
        $chat = Chat::get((int)$room);
        $chat->destroy();

        return response()->json(['message' => 'success']);
    }

    public function read(Request $request)
    {
        $room = $request->get('room_id');

        $chat = Chat::get((int)$room);
        $chat->updateRead();
    }

    public function isRead()
    {
        $notReadRooms = auth()->user()->pet->chatRooms()->wherePivot('is_read', false)->count();
        return response()->json(['is_read' => !$notReadRooms]);
    }

    public function searchChat(Request $request)
    {
        $search = $request->get('search', '');
        $pet = $request->user()->pet;

        $rooms = $pet->chatRooms()->whereHas('pets', function($query) use ($search, $pet) {
            $query->where('id', '<>', $pet->id)->where('name', 'LIKE', "%{$search}%");
        })->get();

        $responseData = ChatRoomResource::collection($rooms)->toArray($request);
        return response()->json($responseData);
    }

    public function likeMessage($room, $message)
    {
        $chat = Chat::get((int)$room);
        $chat->likeMessage((int)$message);

        return response()->json(['message' => 'success']);
    }
}
