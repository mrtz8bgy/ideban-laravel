<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    private const ATTACHMENT_RULE = ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,zip,txt,doc,docx'];

    public function index(Request $request)
    {
        return view('account.tickets.index', [
            'tickets' => Ticket::where('user_id', $request->user()->id)->latest('last_reply_at')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('account.tickets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:190'],
            'category' => ['required', 'in:'.implode(',', Ticket::CATEGORIES)],
            'priority' => ['required', 'in:'.implode(',', Ticket::PRIORITIES)],
            'body' => ['required', 'string', 'max:5000'],
            'attachment' => self::ATTACHMENT_RULE,
        ]);

        $ticket = Ticket::create([
            'reference' => Ticket::newReference(),
            'user_id' => $request->user()->id,
            'subject' => $data['subject'],
            'category' => $data['category'],
            'priority' => $data['priority'],
            'status' => 'open',
            'last_reply_at' => now(),
        ]);

        $this->addMessage($request, $ticket, $data['body'], false);

        return redirect()->route('account.tickets.show', $ticket)->with('success', tr('تیکت ثبت شد.', 'Ticket created.'));
    }

    public function show(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        return view('account.tickets.show', ['ticket' => $ticket->load('messages.user')]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);
        abort_if($ticket->status === 'closed', 422);

        $data = $request->validate(['body' => ['required', 'string', 'max:5000'], 'attachment' => self::ATTACHMENT_RULE]);
        $this->addMessage($request, $ticket, $data['body'], false);
        $ticket->update(['status' => 'open', 'last_reply_at' => now()]);

        return back()->with('success', tr('پیام ارسال شد.', 'Message sent.'));
    }

    /** Attachments stay in private storage and are served only to the ticket owner. */
    public function attachment(Request $request, TicketMessage $message)
    {
        $ticket = $message->ticket;
        abort_unless($ticket && $ticket->user_id === $request->user()->id, 403);
        abort_unless($message->attachment_path && Storage::exists($message->attachment_path), 404);

        return Storage::download($message->attachment_path, $message->attachment_name);
    }

    private function addMessage(Request $request, Ticket $ticket, string $body, bool $isStaff): void
    {
        $path = null;
        $name = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket-attachments');
            $name = mb_substr($request->file('attachment')->getClientOriginalName(), 0, 190);
        }

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'body' => $body,
            'attachment_path' => $path,
            'attachment_name' => $name,
            'is_staff' => $isStaff,
        ]);
    }
}
