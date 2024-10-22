@extends('layouts.app')

@section('title', 'Search Concerns')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="mb-4">Search Concerns</h1>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Search for a Concern</h5>
                    <form action="{{ route('complaints.results') }}" method="GET">
                        <div class="row no-gutters">
                            <div class="col pr-2">
                                <input type="text" class="form-control w-100" id="query" name="query" placeholder="Enter Report Number, Address or Person's Information">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- New Card for Complaints Table -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">All Concerns</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Concern #</th>
                                <th scope="col">Type of Concern</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($complaints as $complaint)
                                <tr>
                                    <th scope="row">{{ $complaint->complaint_number }}</th>
                                    <td>{{ $complaint->complaint_type }}</td>
                                    <td>{{ $complaint->status }}</td>
                                    <td>{{ $complaint->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-primary">View</a>
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
@endsection
