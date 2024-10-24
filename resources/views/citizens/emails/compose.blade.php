@extends('layouts.app')
<div class="container-fluid">
    <div class="row email-app">
        @include('citizens.emails.partials.sidebar')

        <main class="col-sm-9">
            <p class="text-center">New Message</p>
            <form>
                <div class="mb-3 row">
                    <label for="to" class="col-2 col-form-label">To:</label>
                    <div class="col-10">
                        <input type="email" class="form-control" id="to" placeholder="Type email">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cc" class="col-2 col-form-label">CC:</label>
                    <div class="col-10">
                        <input type="email" class="form-control" id="cc" placeholder="Type email">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="bcc" class="col-2 col-form-label">BCC:</label>
                    <div class="col-10">
                        <input type="email" class="form-control" id="bcc" placeholder="Type email">
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-sm-11 ms-auto">
                    <div class="toolbar d-flex justify-content-start align-items-center" role="toolbar">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-type-bold"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-type-italic"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-type-underline"></i>
                            </button>
                        </div>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-text-left"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-text-right"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-text-center"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-justify"></i>
                            </button>
                        </div>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-text-indent-left"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-text-indent-right"></i>
                            </button>
                        </div>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-list-ul"></i>
                            </button>
                            <button type="button" class="btn btn-light">
                                <i class="bi bi-list-ol"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-light me-2">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button type="button" class="btn btn-light me-2">
                            <i class="bi bi-paperclip"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-tags"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">add label <span
                                            class="badge bg-danger">Home</span></a></li>
                                <li><a class="dropdown-item" href="#">add label <span
                                            class="badge bg-info">Job</span></a></li>
                                <li><a class="dropdown-item" href="#">add label <span
                                            class="badge bg-success">Clients</span></a></li>
                                <li><a class="dropdown-item" href="#">add label <span
                                            class="badge bg-warning">News</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 form-group">
                        <textarea class="form-control" id="message" name="body" rows="12" placeholder="Click here to reply"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Send</button>
                        <button type="submit" class="btn btn-light">Draft</button>
                        <button type="submit" class="btn btn-danger">Discard</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
