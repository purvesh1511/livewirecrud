<div>
    <div class="flex justify-between mb-4">
        <div class="flex justify-between mb-4 w-1/4">
            <input type="text" wire:model="search" placeholder="Search Products..."
                class="border rounded ">
            <button wire:click="searching" class="bg-blue-500 text-white px-4 py-2 mx-2 rounded">
                Search
            </button>
            <button wire:click="resetSearch" class="bg-blue-500 text-white px-4 py-2 mx-2 rounded">
                Reset
            </button>
        </div>
        <div class="flex justify-between mb-4"> 
            <button wire:click="openModal" class="bg-blue-500 text-white px-4 py-2 rounded">
                Add Product
            </button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-2">
            {{ session('message') }}
        </div>
    @endif

    <table class="min-w-full bg-white">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-4 border">ID</th>
                <th class="py-2 px-4 border">Name</th>
                <th class="py-2 px-4 border">Price</th>
                <th class="py-2 px-4 border">Status</th>
                <th class="py-2 px-4 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="border px-4 py-2">{{ $product->id }}</td>
                <td class="border px-4 py-2">{{ $product->name }}</td>
                <td class="border px-4 py-2">{{ $product->price }}</td>
                <td class="border px-4 py-2">
                    <button wire:click="toggleStatus({{ $product->id }})"
                            class="px-2 py-1 rounded {{ $product->status == '1' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $product->status ? 'Active' : 'Inactive' }}
                    </button>
                </td>
                
                <td class="border px-4 py-2">
                    <button wire:click="edit({{ $product->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                    <button wire:click="delete({{ $product->id }})" 
                            class="bg-red-500 text-white px-2 py-1 rounded"
                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full z-50">
                <div class="px-6 py-4">
                    <div class="text-lg font-medium text-gray-900 mb-4">
                        {{ $product_id ? 'Edit Product' : 'Add Product' }}
                    </div>

                    <!-- Modal content (inputs) -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model="name" class="mt-1 block w-full border rounded p-2">
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" class="mt-1 block w-full border rounded p-2"></textarea>
                            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" wire:model="price" class="mt-1 block w-full border rounded p-2">
                            @error('price') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" class="mt-1 block w-full border rounded p-2">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" wire:model="image" class="mt-1 block w-full">
                            @if($image)
                                <img src="{{ $image instanceof \Livewire\TemporaryUploadedFile ? $image->temporaryUrl() : asset('storage/products/'.$image) }}" class="w-20 h-20 mt-2 object-cover rounded">
                            @endif
                            @error('image') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-100 flex justify-end">
                    <button wire:click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button wire:click="store" class="bg-blue-500 text-white px-4 py-2 rounded">{{ $product_id ? 'Update' : 'Save' }}</button>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
