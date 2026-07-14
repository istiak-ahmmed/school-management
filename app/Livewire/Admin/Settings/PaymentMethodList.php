<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\PaymentMethod;
use Livewire\Attributes\Layout;

class PaymentMethodList extends Component
{
    public $methods;
    public $showModal = false;
    public $methodId = null;
    public $en_name = '';
    public $bn_name = '';
    public $status = 1;

    public function mount()
    {
        $this->loadMethods();
    }

    public function loadMethods()
    {
        $this->methods = PaymentMethod::all();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $this->methodId = $method->id;
        $this->en_name = $method->en_name;
        $this->bn_name = $method->bn_name;
        $this->status = $method->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'en_name' => 'required|string|max:150|unique:payment_methods,en_name,' . $this->methodId,
            'bn_name' => 'required|string|max:150',
            'status' => 'required|integer|in:0,1',
        ]);

        PaymentMethod::updateOrCreate(
            ['id' => $this->methodId],
            [
                'en_name' => $this->en_name,
                'bn_name' => $this->bn_name,
                'status' => $this->status,
                'is_system' => $this->methodId ? PaymentMethod::find($this->methodId)->is_system : 0,
            ]
        );

        $this->loadMethods();
        $this->resetForm();
        session()->flash('success', 'Payment method saved successfully.');
    }

    public function delete($id)
    {
        $method = PaymentMethod::findOrFail($id);
        if ($method->is_system) {
            session()->flash('error', 'System methods cannot be deleted.');
            return;
        }

        try {
            $method->delete();
            $this->loadMethods();
            session()->flash('success', 'Payment method deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this method as it is being used in transactions.');
        }
    }

    public function toggleStatus($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->status = $method->status == 1 ? 0 : 1;
        $method->save();
        $this->loadMethods();
    }

    public function resetForm()
    {
        $this->methodId = null;
        $this->en_name = '';
        $this->bn_name = '';
        $this->status = 1;
        $this->showModal = false;
    }

    #[Layout('admin.layouts.app')]
    public function render()
    {
        return view('livewire.admin.settings.payment-method-list');
    }
}
