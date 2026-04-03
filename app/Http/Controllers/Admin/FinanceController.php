<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\PaymentVoucher;
use App\Models\AuctionExpense;
use App\Models\FinancialAccount;
use App\Models\Auction;
use App\Models\Negotiation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    // ── MAIN DASHBOARD ────────────────────────────────────────────
    public function dashboard()
    {
        $totalReceived   = Receipt::sum('amount');
        $totalPaid       = PaymentVoucher::sum('amount');
        $totalExpenses   = AuctionExpense::sum('amount');
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $partialInvoices = Invoice::where('status', 'partial')->count();

        $accounts        = FinancialAccount::where('is_active', true)->get();
        $recentReceipts  = Receipt::with(['auction.car', 'financialAccount'])
                            ->latest()->take(8)->get();
        $recentVouchers  = PaymentVoucher::with(['auction.car', 'financialAccount'])
                            ->latest()->take(8)->get();

        // Monthly stats (last 6 months)
        $monthlyStats = collect(range(5, 0))->map(function ($monthsAgo) {
            $date  = now()->subMonths($monthsAgo);
            return [
                'month'    => $date->format('M Y'),
                'received' => Receipt::whereYear('receipt_date', $date->year)
                                     ->whereMonth('receipt_date', $date->month)
                                     ->sum('amount'),
                'paid'     => PaymentVoucher::whereYear('voucher_date', $date->year)
                                            ->whereMonth('voucher_date', $date->month)
                                            ->sum('amount'),
            ];
        });

        return view('admin.finance.dashboard', compact(
            'totalReceived', 'totalPaid', 'totalExpenses',
            'pendingInvoices', 'partialInvoices',
            'accounts', 'recentReceipts', 'recentVouchers', 'monthlyStats'
        ));
    }

    // ── INVOICES ──────────────────────────────────────────────────
    public function invoices(Request $request)
    {
        $query  = Invoice::with(['auction.car', 'user'])->latest();
        $status = $request->get('status');
        if ($status) $query->where('status', $status);
        $invoices = $query->paginate(20);
        $accounts = FinancialAccount::where('is_active', true)->get();
        return view('admin.finance.invoices', compact('invoices', 'accounts'));
    }

    /** Auto-create invoice from a negotiation */
    public function createInvoiceFromNegotiation(Negotiation $negotiation)
    {
        if (Invoice::where('negotiation_id', $negotiation->id)->exists()) {
            return back()->with('error', 'Invoice already exists for this negotiation.');
        }
        $invoice = Invoice::createFromNegotiation($negotiation);
        return redirect()->route('admin.finance.invoice.show', $invoice)
                         ->with('success', 'Invoice ' . $invoice->invoice_number . ' created.');
    }

    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['auction.car', 'user', 'negotiation', 'receipts.financialAccount']);
        $expenses = AuctionExpense::where('auction_id', $invoice->auction_id)->latest()->get();
        $vouchers = PaymentVoucher::where('auction_id', $invoice->auction_id)->latest()->get();
        $accounts = FinancialAccount::where('is_active', true)->get();
        return view('admin.finance.invoice-show', compact('invoice', 'expenses', 'vouchers', 'accounts'));
    }

    public function updateInvoice(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'due_date'        => 'nullable|date',
            'internal_notes'  => 'nullable|string|max:1000',
            'lead_asking_price' => 'nullable|numeric|min:0',
        ]);
        // Recalc gross profit if lead price changes
        if (isset($data['lead_asking_price'])) {
            $data['gross_profit'] = (float)$invoice->dealer_final_price - (float)$data['lead_asking_price'];
            $data['net_profit']   = $data['gross_profit'] - (float)$invoice->total_expenses;
        }
        $invoice->update($data);
        return back()->with('success', 'Invoice updated.');
    }

    // ── RECEIPTS (سندات قبض) ──────────────────────────────────────
    public function receipts(Request $request)
    {
        $receipts = Receipt::with(['auction.car', 'financialAccount', 'receivedFromUser'])
                    ->latest()->paginate(25);
        $accounts = FinancialAccount::where('is_active', true)->get();
        return view('admin.finance.receipts', compact('receipts', 'accounts'));
    }

    public function storeReceipt(Request $request)
    {
        $data = $request->validate([
            'auction_id'           => 'nullable|exists:auctions,id',
            'invoice_id'           => 'nullable|exists:invoices,id',
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'received_from_name'   => 'nullable|string|max:200',
            'amount'               => 'required|numeric|min:0.01',
            'payment_method'       => 'required|in:cash,transfer,cheque,pos',
            'reference'            => 'nullable|string|max:100',
            'receipt_date'         => 'required|date',
            'purpose'              => 'required|string|max:100',
            'notes'                => 'nullable|string|max:500',
        ]);

        $data['receipt_number'] = Receipt::generateNumber();
        $data['created_by']     = Auth::id();

        $receipt = Receipt::create($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'receipt' => $receipt->fresh()]);
        }
        return back()->with('success', 'Receipt ' . $receipt->receipt_number . ' recorded.');
    }

    // ── PAYMENT VOUCHERS (سندات صرف) ─────────────────────────────
    public function vouchers(Request $request)
    {
        $vouchers = PaymentVoucher::with(['auction.car', 'financialAccount'])
                    ->latest()->paginate(25);
        $accounts = FinancialAccount::where('is_active', true)->get();
        return view('admin.finance.vouchers', compact('vouchers', 'accounts'));
    }

    public function storeVoucher(Request $request)
    {
        $data = $request->validate([
            'auction_id'           => 'nullable|exists:auctions,id',
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'paid_to_name'         => 'required|string|max:200',
            'amount'               => 'required|numeric|min:0.01',
            'payment_method'       => 'required|in:cash,transfer,cheque',
            'reference'            => 'nullable|string|max:100',
            'voucher_date'         => 'required|date',
            'category'             => 'required|string|max:50',
            'description'          => 'nullable|string|max:500',
        ]);

        $data['voucher_number'] = PaymentVoucher::generateNumber();
        $data['created_by']     = Auth::id();

        $voucher = PaymentVoucher::create($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'voucher' => $voucher->fresh()]);
        }
        return back()->with('success', 'Voucher ' . $voucher->voucher_number . ' recorded.');
    }

    // ── EXPENSES ──────────────────────────────────────────────────
    public function storeExpense(Request $request)
    {
        $data = $request->validate([
            'auction_id'   => 'required|exists:auctions,id',
            'category'     => 'required|string|max:50',
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'receipt_ref'  => 'nullable|string|max:100',
        ]);
        $data['created_by'] = Auth::id();
        $expense = AuctionExpense::create($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'expense' => $expense->fresh()]);
        }
        return back()->with('success', 'Expense added.');
    }

    public function destroyExpense(AuctionExpense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Expense removed.');
    }

    // ── FINANCIAL ACCOUNTS ────────────────────────────────────────
    public function accounts()
    {
        $accounts = FinancialAccount::withCount(['receipts', 'paymentVouchers'])->get();
        return view('admin.finance.accounts', compact('accounts'));
    }

    public function storeAccount(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'type'             => 'required|in:cash,bank,other',
            'bank_name'        => 'nullable|string|max:100',
            'account_number'   => 'nullable|string|max:50',
            'opening_balance'  => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string|max:500',
        ]);
        $data['current_balance'] = $data['opening_balance'] ?? 0;
        $account = FinancialAccount::create($data);
        return back()->with('success', 'Account "' . $account->name . '" created.');
    }
}
