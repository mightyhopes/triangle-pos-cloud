@extends('layouts.app')

@section('title', 'Sales')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h3>Sales</h3>
                        @can('create-sales')
                            <div class="action-buttons">
                                <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                    Add Sale <i class="bi bi-plus"></i>
                                </a>
                                <a href="{{ route('sales.pos') }}" class="btn btn-primary">
                                    POS System <i class="bi bi-cart3"></i>
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Payment Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $sale)
                                        <tr>
                                            <td data-label="Date">{{ $sale->date }}</td>
                                            <td data-label="Reference">{{ $sale->reference }}</td>
                                            <td data-label="Customer">{{ $sale->customer->name }}</td>
                                            <td data-label="Status">{{ $sale->status }}</td>
                                            <td data-label="Total">{{ format_currency($sale->total_amount) }}</td>
                                            <td data-label="Paid">{{ format_currency($sale->paid_amount) }}</td>
                                            <td data-label="Due">{{ format_currency($sale->due_amount) }}</td>
                                            <td data-label="Payment Status">
                                                <span class="badge badge-{{ $sale->payment_status == 'Paid' ? 'success' : 'danger' }}">
                                                    {{ $sale->payment_status }}
                                                </span>
                                            </td>
                                            <td data-label="Actions" class="actions">
                                                <div class="btn-group">
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button id="delete" class="btn btn-danger btn-sm" onclick="
                                                        event.preventDefault();
                                                        if (confirm('Are you sure you want to delete this sale?')) {
                                                        document.getElementById('delete-form-{{ $sale->id }}').submit();
                                                        }
                                                        ">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $sale->id }}" action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 