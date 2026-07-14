<?php

namespace App\Livewire\Admin\Finance\ExpenseCategory;

use App\Models\ExpenseCategory;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public $name = '';
    public $description = '';
    public $is_active = 1;
    public $categoryId = null;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'integer|in:0,1',
    ];

    public function openModal()
    {
        $this->reset(['name', 'description', 'is_active', 'categoryId']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(ExpenseCategory $category)
    {
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        ExpenseCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]
        );

        $this->showModal = false;
        session()->flash('message', 'Category saved successfully.');
    }

    public function toggleActive(ExpenseCategory $category)
    {
        $category->update(['is_active' => $category->is_active === 1 ? 0 : 1]);
    }

    public function delete(ExpenseCategory $category)
    {
        if ($category->expenses()->count() > 0) {
            session()->flash('error', 'Cannot delete category with associated expenses.');
            return;
        }
        $category->delete();
        session()->flash('message', 'Category deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.finance.expense-category.category-manager', [
            'categories' => ExpenseCategory::orderBy('name')->paginate(10),
        ])->layout('admin.layouts.app');
    }
}
