@extends('teacher.dashboard')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1>{{ isset($package) ? 'Edit Package' : 'Add Package' }}</h1>

    <form action="{{ isset($package) ? route('packages.update', $package->id) : route('packages.store') }}" method="POST">
        @csrf
        @if (isset($package))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Package Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $package->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" name="price" value="{{ old('price', $package->price ?? '') }}" step="0.01" class="form-control" placeholder="Price" required>
        </div>

        <button type="submit" class="btn btn-success">{{ isset($package) ? 'Update Package' : 'Add Package' }}</button>
    </form>

    <h2 class="mt-5">Packages List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $package)
                <tr>
                    <td>{{ $package->id }}</td>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->price }}</td>
                    <td>
                        <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('packages.destroy', $package->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this package?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $packages->links() }} 
</div>
@endsection