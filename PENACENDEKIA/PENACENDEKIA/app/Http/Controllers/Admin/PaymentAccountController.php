<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PaymentAccountController extends Controller
{
    public function index()
    {
        $accounts = PaymentAccount::latest()->get();
        return view('admin.payment_accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:100'],
        ], [
            'bank_name.required' => 'Nama Bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $account = PaymentAccount::create([
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'is_active' => true,
        ]);

        ActivityLog::record('payment_account.created', "Admin menambahkan rekening bank {$account->bank_name} - {$account->account_number}", $account);

        return back()->with('success', "Rekening bank {$account->bank_name} berhasil ditambahkan!");
    }

    public function toggleStatus(PaymentAccount $account)
    {
        $account->is_active = !$account->is_active;
        $account->save();

        $statusText = $account->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::record('payment_account.status_changed', "Status rekening bank {$account->bank_name} {$statusText}", $account);

        return back()->with('success', "Rekening {$account->bank_name} berhasil {$statusText}.");
    }
}
