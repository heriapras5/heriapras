<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $messages = Message::when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('service', 'like', "%{$search}%"))
            ->when($status === 'unread', fn($q) => $q->where('is_read', false))
            ->when($status === 'read', fn($q) => $q->where('is_read', true))
            ->orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = Message::where('is_read', false)->count();
        return view('admin.messages.index', compact('messages', 'unreadCount', 'status'));
    }

    public function show(Message $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        return view('admin.messages.show', compact('message'));
    }

    public function toggleRead(Request $request, Message $message)
    {
        $message->update(['is_read' => !$message->is_read]);
        return back()->with('success', $message->is_read ? 'Pesan ditandai sudah dibaca.' : 'Pesan ditandai belum dibaca.');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
