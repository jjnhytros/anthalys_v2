<div class="container">
    <div class="row">
        @include('citizens.emails.partials.sidebar')

        <div class="col-sm-9">
            <!-- Star form compose mail -->
            <form class="form-horizontal">
                <div class="shadow-sm card email-inbox">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">View Mail</h5>
                        <div>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search mail">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 card-subheader d-flex justify-content-between align-items-center">
                        <h5 class="m-0 lead">Blankon Fullpack Admin Theme</h5>
                        <div>
                            <button class="btn btn-info btn-sm" type="button" title="Print">
                                <i class="bi bi-printer"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" type="button" title="Trash">
                                <i class="bi bi-trash"></i>
                            </button>
                            <a href="#mail-compose.html" class="btn btn-success btn-sm">
                                <i class="bi bi-reply"></i> Reply
                            </a>
                        </div>
                    </div>
                    <div class="p-2 card-subheader">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-7 d-flex align-items-center">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Sender"
                                    class="rounded-circle me-2" width="40">
                                <span>maildjavaui@gmail.com</span>
                                <span class="mx-2">to</span>
                                <strong>me</strong>
                            </div>
                            <div class="col-md-4 col-sm-4 col-5 text-end">
                                <p class="mb-0">10:15AM 02 FEB 2014</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="view-mail">
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry...
                            </p>
                            <p>
                                It is a long established fact that a reader will be distracted by the readable
                                content...
                            </p>
                        </div>
                        <div class="attachment-mail">
                            <p>
                                <span><i class="bi bi-paperclip"></i> 3 attachments — </span>
                                <a href="#">Download all attachments</a> |
                                <a href="#">View all images</a>
                            </p>
                            <ul class="list-unstyled">
                                <li class="mb-2 d-flex align-items-center">
                                    <a class="atch-thumb" href="#" data-bs-toggle="modal"
                                        data-bs-target="#photo1">
                                        <img src="https://www.bootdey.com/image/200x200/" alt="..."
                                            class="img-thumbnail me-2" width="100">
                                    </a>
                                    <div>
                                        <a class="d-block fw-bold" href="#">IMG_001.jpg</a>
                                        <span>20KB</span>
                                        <div class="links">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#photo1">View</a> -
                                            <a href="#">Download</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <a class="atch-thumb" href="#" data-bs-toggle="modal"
                                        data-bs-target="#photo2">
                                        <img src="https://www.bootdey.com/image/200x200/" alt="..."
                                            class="img-thumbnail me-2" width="100">
                                    </a>
                                    <div>
                                        <a class="d-block fw-bold" href="#">IMG_002.jpg</a>
                                        <span>15KB</span>
                                        <div class="links">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#photo2">View</a> -
                                            <a href="#">Download</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <a class="atch-thumb" href="#" data-bs-toggle="modal"
                                        data-bs-target="#photo3">
                                        <img src="https://www.bootdey.com/image/200x200/" alt="..."
                                            class="img-thumbnail me-2" width="100">
                                    </a>
                                    <div>
                                        <a class="d-block fw-bold" href="#">IMG_003.jpg</a>
                                        <span>13KB</span>
                                        <div class="links">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#photo3">View</a> -
                                            <a href="#">Download</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="#mail-compose.html" class="btn btn-success btn-sm"><i class="bi bi-reply"></i>
                            Reply</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-arrow-right"></i>
                            Forward</button>
                        <button class="btn btn-info btn-sm" type="button" title="Print">
                            <i class="bi bi-printer"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" type="button" title="Trash">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!--/ End form compose mail -->
        </div>
    </div>
</div>
