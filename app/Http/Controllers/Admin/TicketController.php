<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignee'])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_staff', false)->whereNull('read_at');
            }])
            ->latest('last_reply_at');
        if ($request->filled('status') && in_array($request->query('status'), Ticket::STATUSES, true)) {
            $query->where('status', $request->query('status'));
        }

        return view('admin.tickets.index', ['tickets' => $query->paginate(20), 'statuses' => Ticket::STATUSES]);
    }

    public function show(Ticket $ticket)
    {
        $ticket->messages()
            ->where('is_staff', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.tickets.show', [
            'ticket' => $ticket->load(['user', 'assignee', 'messages.user']),
            'staff' => User::whereIn('role', ['admin', 'content', 'sales'])->orderBy('name')->get(),
            'statuses' => Ticket::STATUSES,
            'priorities' => Ticket::PRIORITIES,
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,zip,txt,doc,docx'],
        ]);

        $path = null;
        $name = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket-attachments');
            $name = mb_substr($request->file('attachment')->getClientOriginalName(), 0, 190);
        }

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'attachment_path' => $path,
            'attachment_name' => $name,
            'is_staff' => true,
        ]);

        $ticket->update([
            'status' => 'answered',
            'first_response_at' => $ticket->first_response_at ?? now(),
            'last_reply_at' => now(),
            'assigned_to' => $ticket->assigned_to ?? $request->user()->id,
        ]);

        return back()->with('success', tr('پاسخ ارسال شد.', 'Reply sent.'));
    }

    public function attachment(TicketMessage $message)
    {
        abort_unless($message->attachment_path && Storage::exists($message->attachment_path), 404);

        return Storage::download($message->attachment_path, $message->attachment_name);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Ticket::STATUSES)],
            'priority' => ['required', Rule::in(Ticket::PRIORITIES)],
            'assigned_to' => ['nullable', Rule::exists('users', 'id')],
        ]);
        $data['closed_at'] = $data['status'] === 'closed'
            ? ($ticket->closed_at ?? now())
            : null;
        $ticket->update($data);

        return back()->with('success', tr('تیکت به‌روزرسانی شد.', 'Ticket updated.'));
    }
}
