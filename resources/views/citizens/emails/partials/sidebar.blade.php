            <div class="col-sm-3">
                <a href="{{ route('email.compose') }}" class="m-2 mb-3 btn btn-success"><i class="bi bi-plus-lg"></i>
                    Compose
                    Email</a>
                <ul class="mb-3 shadow nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('email.inbox') }}" class="nav-link active">
                            <i class="bi bi-inbox"></i> Inbox <span class="badge bg-primary float-end">7</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('email.compose') }}" class="nav-link">
                            <i class="bi bi-envelope"></i> Send Mail
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-star"></i> Important
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-file-earmark"></i> Drafts
                            <span class="badge bg-info float-end">35</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-trash"></i> Trash
                        </a>
                    </li>
                </ul>

                <h5 class="mb-2">More</h5>
                <ul class="shadow nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-folder2-open"></i> Promotions
                            <span class="badge bg-danger float-end">3</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-folder2-open"></i> Job list
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-folder2-open"></i> Backup
                        </a>
                    </li>
                </ul>
            </div>
