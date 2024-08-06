<form action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $product->name }}" placeholder="Product Name">
    <input type="text" name="slug" value="{{ $product->slug }}" placeholder="Product Slug">
    <textarea name="description" placeholder="Product Description">{{ $product->description }}</textarea>
    <button type="submit">Update Product</button>
</form>
