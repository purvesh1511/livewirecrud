<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProductCrud extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $description, $price, $status = 1, $image, $product_id;
    public $isModalOpen = false;
    public $search = '';

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'status' => 'nullable|in:1,0', // 1 for Active, 0 for Inactive
        'image' => 'nullable|image|max:2048',
    ];

    public function searching()
    {
        $this->resetPage();
    }
    
    // 🔥 Reset pagination when searching
    public function resetSearch()
    {
        $this->search = '';
        $this->searching();
    }

    public function render()
    {
        $products = Product::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product-crud', compact('products'));
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->status = 1;
        $this->image = null;
        $this->product_id = null;
    }

    public function store()
    {
        $validated = $this->validate();
        
        if ($this->image instanceof \Livewire\TemporaryUploadedFile) {
            $filename = Str::random(10) . '.' . $this->image->getClientOriginalExtension();
            $this->image->storeAs('products', $filename, 'public');
            $validated['image'] = $filename;
        }

        $validated['user_id'] = Auth::id();

        Product::updateOrCreate(
            ['id' => $this->product_id],
            $validated
        );

        session()->flash('message', $this->product_id ? 'Product Updated.' : 'Product Created.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $this->product_id = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->status = $product->status;
        $this->image = $product->image;

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            \Storage::disk('public')->delete('products/' . $product->image);
        }

        $product->delete();
        session()->flash('message', 'Product Deleted.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->status === 'Active' ? 'Inactive' : 'Active';
        $product->save();
    }
}
