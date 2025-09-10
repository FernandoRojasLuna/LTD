<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReceived;
use Illuminate\Support\Facades\Config;

class ContactController extends Controller
{
    // Admin: paginated list of contacts (JSON)
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $query = Contact::query();

        // filter: unread
        if ($request->get('filter') === 'unread') {
            $query->whereNull('read_at');
        }

        // search by name or email or message
        if ($q = $request->get('search')) {
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $unreadCount = Contact::whereNull('read_at')->count();

        return response()->json(array_merge($contacts->toArray(), ['unread_count' => $unreadCount]));
    }

    // Admin: mark as read
    public function markRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->read_at = now();
        $contact->save();
        return response()->json(['message' => 'Marked as read']);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['ip'] = $request->ip();
        $data['user_agent'] = $request->header('User-Agent');

        $contact = Contact::create($data);

        // send notification mail to configured address (MAIL_TO) or fallback to MAIL_FROM
        try {
            $to = Config::get('mail.to.address') ?: Config::get('mail.from.address');
            if ($to) {
                Mail::to($to)->send(new ContactReceived($contact));
            }
        } catch (\Exception $e) {
            // don't break the response if mail fails; it will be logged by Laravel
        }

        return response()->json(['message' => 'Contact received', 'id' => $contact->id], 201);
    }
}
