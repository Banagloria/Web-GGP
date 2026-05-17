<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Contact::query()->orderByDesc('id');

        if ($request->query('filter') === 'unread') {
            $query->whereNull('read_at');
        }

        $items = $query->paginate(20)->withQueryString();

        return view('admin.contacts.index', compact('items'));
    }

    public function show(Contact $contact): View
    {
        if ($contact->read_at === null) {
            $contact->update(['read_at' => now()]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('dashboard.kontak.index')->with('status', 'Pesan dihapus.');
    }
}
