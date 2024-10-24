@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('citizens.emails.partials.sidebar')
            <div class="mt-2 col-sm-9">
                <div class="shadow-sm card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Inbox (7)</h5>
                        <form action="{{ route('email.search') }}" class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search mail" aria-label="Search">
                            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                    <div class="p-2 card-subheader d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox-group">
                                <label class="form-check-label" for="checkbox-group"></label>
                            </div>
                            <div class="btn-group ms-2">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    All
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">None</a></li>
                                    <li><a class="dropdown-item" href="#">Read</a></li>
                                    <li><a class="dropdown-item" href="#">Unread</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary btn-sm" type="button">
                                    <i class="bi bi-archive"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" type="button">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" type="button">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="btn-group ms-2">
                                <button class="btn btn-outline-secondary btn-sm">More</button>
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-check-circle"></i> Mark as
                                            read</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-shield-exclamation"></i>
                                            Spam</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-trash"></i> Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="p-0 card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <tbody>
                                    <!-- Esempio di riga email -->
                                    @foreach ($emails as $email)
                                        <tr class="{{ $email->status === 'unread' ? 'table-primary' : '' }}">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>
                                            <td><i
                                                    class="bi bi-star{{ $email->status === 'important' ? '-fill' : '' }}"></i>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                        alt="avatar" class="rounded-circle me-2" width="40">
                                                    <div>
                                                        <h6 class="mb-1 text-primary">{{ $email->subject }}</h6>
                                                        <p class="mb-0 text-muted">{{ Str::limit($email->message, 50) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted">{{ $email->created_at->format('M d, Y') }}</td>
                                            <td><i class="bi bi-paperclip"></i></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <span class="text-muted">Showing 1-50 of {{ $totalEmails }} messages</span>
                        <div class="btn-group">
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i
                                    class="bi bi-chevron-left"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i
                                    class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
