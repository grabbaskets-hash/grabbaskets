@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Delivery Partners</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deliveryPartners as $partner)
                                <tr>
                                    <td>{{ $partner->id }}</td>
                                    <td>{{ $partner->name }}</td>
                                    <td>{{ $partner->phone }}</td>
                                    <td>{{ $partner->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $partner->status === 'approved' ? 'success' : ($partner->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($partner->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $partner->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.delivery-partners.show', $partner) }}" class="btn btn-sm btn-info">
                                            View
                                        </a>
                                        <a href="{{ route('admin.delivery-partners.documents', $partner) }}" class="btn btn-sm btn-secondary">
                                            Documents
                                        </a>
                                        @if($partner->status === 'pending')
                                        <form action="{{ route('admin.delivery-partners.status', $partner) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.delivery-partners.status', $partner) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Reject
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $deliveryPartners->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection