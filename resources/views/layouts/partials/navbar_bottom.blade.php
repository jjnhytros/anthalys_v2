{{-- resources/views/layouts/partials/navbar_bottom.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-bottom">
    <div class="container-fluid">
        <div class="navbar-brand">
            <i class="bi bi-person-circle"></i>
            {{-- {{ auth()->user()->name }} --}}
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#citizenNavbar"
            aria-controls="citizenNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="citizenNavbar">
            <!-- Dropup del telefono -->
            <div class="dropup ms-auto">
                <button class="btn btn-light phone-icon" id="phoneDropup" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-phone"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-3 phone-screen" aria-labelledby="phoneDropup">
                    <!-- Header con l'ora -->
                    <div class="d-flex justify-content-between align-items-center phone-header">
                        <span class="phone-time">{{ now()->format('H:i') }}</span>
                        <i class="bi bi-battery-half"></i>
                    </div>

                    <!-- App sullo schermo -->
                    <div class="row text-center phone-apps mt-2">
                        <div class="col-4">
                            <a href="#" class="btn btn-light phone-app">
                                <i class="bi bi-cash-stack"></i>
                                <span class="phone-app-label">Cash</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#" class="btn btn-light phone-app">
                                <i class="bi bi-bank"></i>
                                <span class="phone-app-label">Bank</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="#" class="btn btn-light phone-app">
                                <i class="bi bi-person"></i>
                                <span class="phone-app-label">Profile</span>
                            </a>
                        </div>
                        <div class="col-4 mt-2">
                            <a href="#" class="btn btn-light phone-app">
                                <i class="bi bi-envelope"></i>
                                <span class="phone-app-label">Messages</span>
                            </a>
                        </div>
                        <div class="col-4 mt-2">
                            <a href="#" class="btn btn-light phone-app">
                                <i class="bi bi-calendar"></i>
                                <span class="phone-app-label">Calendar</span>
                            </a>
                        </div>
                        <div class="col-4 mt-2">
                            <a href="#" class="btn btn-light phone-app">
                                <i class="bi bi-gear"></i>
                                <span class="phone-app-label">Settings</span>
                            </a>
                        </div>
                    </div>

                    <!-- Icone in basso per chiamata e contatti (dockbar) -->
                    <div class="d-flex justify-content-evenly phone-dockbar">
                        <a href="#" class="btn btn-success rounded-circle p-2 small-btn">
                            <i class="bi bi-telephone-fill"></i>
                        </a>
                        <a href="#" class="btn btn-primary rounded-circle p-2 small-btn">
                            <i class="bi bi-person-lines-fill"></i>
                        </a>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</nav>
