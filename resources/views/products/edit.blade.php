<form action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Product Name -->
    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" placeholder="Product Name" required>
    </div>

    <!-- Product Description -->
    <div class="mb-3">
        <label for="description" class="form-label">Product Description</label>
        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Product Description" required>{{ old('description', $product->description) }}</textarea>
    </div>

    <!-- Category Dropdown -->
    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select id="category_id" name="category_id" class="form-select" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $product->categories->first()->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tags Checkboxes -->
    <div class="mb-3">
        <label class="form-label">Tags</label>
        <div class="form-check">
            @foreach($tags as $tag)
                <input class="form-check-input" type="checkbox" id="tag{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                    {{ $product->tags->pluck('id')->contains($tag->id) ? 'checked' : '' }}>
                <label class="form-check-label" for="tag{{ $tag->id }}">
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>
    </div>

    <!-- Submit and Back Buttons -->
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</form>
