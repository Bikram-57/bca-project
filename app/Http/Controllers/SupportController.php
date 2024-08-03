<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SupportTicketCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = Support::where('user_id', Auth::id())->orderByDesc('created_at')->paginate(10);
        return view('support.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'attachment' => 'nullable|image|max:3072',
        ]);

        $support = new Support();
        $support->user_id = Auth::id();
        $support->subject = $request->subject;
        $support->description = $request->description;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('assets/support_files');

            // Create the directory if it doesn't exist
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            $support->attachment = $filename;
        }

        $support->save();

        // Send email notification to developers
        $developers = ['kunsangmoktan11@gmail.com', 'dev.kunsang@gmail.com'];
        Mail::to($developers[0])
            ->cc($developers[1])
            ->send(new SupportTicketCreated($support, Auth::user()->name));

        return back()->withSuccess('Support ticket created successfully!');
    }

    public function dashboard()
    {
        $tickets = Support::with('user')->orderByDesc('created_at')->get();
        $pending = Support::where('status', 0)->count();
        $resolved = Support::where('status', 2)->count();
        return view("support.dashboard", compact('tickets', 'pending', 'resolved'));
    }

    public function allTickets()
    {
        $tickets = Support::with('user')->orderByDesc('created_at')->get();
        $label = 'All';
        return view('support.tickets', compact('tickets', 'label'));
    }
    public function pending()
    {
        $tickets = Support::with('user')->where('status', 0)->orderByDesc('created_at')->get();
        $label = 'Pending';
        return view('support.tickets', compact('tickets', 'label'));
    }
    public function resolved()
    {
        $tickets = Support::with('user')->where('status', 2)->orderByDesc('created_at')->get();
        $label = 'Resolved';
        return view('support.tickets', compact('tickets', 'label'));
    }

    public function view($id)
    {
        $ticket = Support::with('user')->where('id', $id)->first();
        return view('support.view', compact('ticket'));
    }
    public function updateStatus(Request $request, $id)
    {
        Support::where('id', $id)->update(['status' => $request->status]);
        return back()->withSuccess('Status updated successfully');
    }
}
