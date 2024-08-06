<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <input type="text" name="title" placeholder="Product Title" required>
    <input type="text" name="category" placeholder="Product Category" required>
    <input type="text" name="tag" placeholder="Product Tag" required>
    <textarea name="description" placeholder="Product Description" required></textarea>
    <button type="submit">Create Product</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
</form>

