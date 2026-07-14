<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserPaymentAccount;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

class PaymentAccounts extends Component
{
    public $accounts;
    public $availableMethods;

    public $showForm = false;
    public $accountId = null;
    public $payment_method_id = '';
    public $account_name = '';
    public $account_number = '';
    public $is_default = false;

    public function mount()
    {
        $this->loadAccounts();
        $this->availableMethods = PaymentMethod::where('status', 1)->get();
    }

    public function loadAccounts()
    {
        $this->accounts = UserPaymentAccount::with('paymentMethod')
            ->where('user_id', Auth::id())
            ->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $account = UserPaymentAccount::where('user_id', Auth::id())->findOrFail($id);
        $this->accountId = $account->id;
        $this->payment_method_id = $account->payment_method_id;
        $this->account_name = $account->account_name;
        $this->account_number = $account->account_number;
        $this->is_default = $account->is_default;
        
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
        ]);

        if ($this->is_default) {
            UserPaymentAccount::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        UserPaymentAccount::updateOrCreate(
            ['id' => $this->accountId, 'user_id' => Auth::id()],
            [
                'payment_method_id' => $this->payment_method_id,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
                'is_default' => $this->is_default,
                'user_id' => Auth::id(),
            ]
        );

        $this->loadAccounts();
        $this->resetForm();
        session()->flash('message', 'Payment account saved successfully.');
    }

    public function delete($id)
    {
        UserPaymentAccount::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->loadAccounts();
    }

    public function resetForm()
    {
        $this->accountId = null;
        $this->payment_method_id = '';
        $this->account_name = '';
        $this->account_number = '';
        $this->is_default = false;
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.profile.payment-accounts');
    }
}
