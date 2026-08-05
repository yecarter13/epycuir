<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        $customers = Order::whereNotNull('customer_email')
            ->select('customer_email', 'customer_name')
            ->distinct()
            ->orderBy('customer_name')
            ->get();

        $recentOrders = Order::with('items')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.mail.index', compact('customers', 'recentOrders'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        $body = nl2br(e($request->message));

        try {
            Mail::to($request->recipient)->send(new AdminMail($request->subject, $body));
            return back()->with('success', 'Email envoyé à ' . $request->recipient);
        } catch (\Throwable $e) {
            return back()->with('error', 'Échec de l\'envoi : ' . $e->getMessage());
        }
    }
}
