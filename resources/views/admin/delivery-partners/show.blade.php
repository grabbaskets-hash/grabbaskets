@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Delivery Partner Details</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $deliveryPartner->id }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $deliveryPartner->name }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $deliveryPartner->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $deliveryPartner->email }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $deliveryPartner->status === 'approved' ? 'success' : ($deliveryPartner->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($deliveryPartner->status) }}
                                </span>
                                @if($deliveryPartner->status === 'pending')
                                <div class="mt-2">
                                    <form action="{{ route('admin.delivery-partners.status', $deliveryPartner) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.delivery-partners.status', $deliveryPartner) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Documents</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($deliveryPartner->id_proof)
                        <div class="col-md-6">
                            <h5>ID Proof</h5>
                            <img src="{{ Storage::disk('public')->url($deliveryPartner->id_proof) }}" class="img-fluid mb-3">
                        </div>
                        @endif
                        @if($deliveryPartner->address_proof)
                        <div class="col-md-6">
                            <h5>Address Proof</h5>
                            <img src="{{ Storage::disk('public')->url($deliveryPartner->address_proof) }}" class="img-fluid mb-3">
                        </div>
                        @endif
                        @if($deliveryPartner->vehicle_rc)
                        <div class="col-md-6">
                            <h5>Vehicle RC</h5>
                            <img src="{{ Storage::disk('public')->url($deliveryPartner->vehicle_rc) }}" class="img-fluid">
                        </div>
                        @endif
                        @if($deliveryPartner->driving_license)
                        <div class="col-md-6">
                            <h5>Driving License</h5>
                            <img src="{{ Storage::disk('public')->url($deliveryPartner->driving_license) }}" class="img-fluid">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Delivery Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Deliveries</span>
                                    <span class="info-box-number">{{ $deliveryPartner->deliveries->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Earnings</span>
                                    <span class="info-box-number">₹{{ number_format($deliveryPartner->wallet->total_earnings ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Current Balance</span>
                                    <span class="info-box-number">₹{{ number_format($deliveryPartner->wallet->balance ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Rating</span>
                                    <span class="info-box-number">{{ number_format($deliveryPartner->average_rating ?? 0, 1) }} / 5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection