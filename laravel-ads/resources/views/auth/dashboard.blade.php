@extends('layouts') 

@section('content')
    <div class="container">
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if(Auth::check())
            <h1>Welcome to the Dashboard, {{ Auth::user()->name }}!</h1>

            <form action="{{ route('dashboard') }}" method="GET">
                <div class="form-group">
                    <label for="title">Search by Title:</label>
                    <input type="text" name="title" class="form-control" value="{{ $title ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="type">Search by Type:</label>
                    <input type="text" name="type" class="form-control" value="{{ $type ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($advertisements as $advertisement)
                        <tr>
                            <td>{{ $advertisement->title }}</td>
                            <td>{{ $advertisement->description }}</td>
                            <!-- Add more table cells as needed -->
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No advertisements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            {{ $advertisements->links() }}
        @else
            <p>You do not have access to the dashboard. Please log in.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        @endif
    </div>
@endsection
