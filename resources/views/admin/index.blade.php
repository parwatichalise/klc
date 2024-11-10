@extends('layouts.app') 

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">Payments List</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User_ID</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->user_id }}</td>
                                <td>{{ $payment->product_id }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>{{ $payment->created_at }}</td>
                                <td>{{ $payment->status }}</td>
                                <td>
                                    <form action="{{ route('payment.toggleStatus', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $payment->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $payment->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection