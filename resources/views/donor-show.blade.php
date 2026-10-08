<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $donor->name }} | Donor Profile</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f6f8fc;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .navbar {
            background: linear-gradient(
                135deg,
                #720000,
                #b00020,
                #e63946
            );
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
            font-weight: 700;
        }

        .page {
            padding: 45px 0;
        }

        .profile {
            background: white;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,.08);
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #fff0f2;
            color: #c1121f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            margin: auto;
        }

        .blood {
            background: #c1121f;
            color: white;
            border-radius: 25px;
            padding: 8px 16px;
            font-weight: 800;
        }

        .btn-main {
            background: #c1121f;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 11px 25px;
            font-weight: 700;
        }

        .btn-main:hover {
            background: #97000c;
            color: white;
        }

    </style>

</head>

<body>

<nav class="navbar">

    <div class="container">

        <a
            class="navbar-brand"
            href="{{ route('dashboard') }}"
        >
            🩸 BloodNexus
        </a>

        <a
            href="{{ route('blood.search') }}"
            class="btn btn-light rounded-pill"
        >
            Back to Donors
        </a>

    </div>

</nav>


<div class="container page">

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <div class="profile text-center">

        <div class="avatar">
            🩸
        </div>

        <h2 class="fw-bold mt-3">
            {{ $donor->name }}
        </h2>

        <div class="mb-4">

            <span class="blood">
                {{ $donor->blood_group }}
            </span>

        </div>


        <div class="row g-4 text-start mt-2">

            <div class="col-md-4">

                <strong>
                    Blood Group
                </strong>

                <div class="text-muted">
                    {{ $donor->blood_group }}
                </div>

            </div>


            <div class="col-md-4">

                <strong>
                    City
                </strong>

                <div class="text-muted">
                    {{ $donor->city ?: 'Not specified' }}
                </div>

            </div>


            <div class="col-md-4">

                <strong>
                    Phone
                </strong>

                <div class="text-muted">
                    {{ $donor->phone }}
                </div>

            </div>

        </div>


        <div class="mt-5">

            @if($donor->is_available)

                <div class="text-success fw-bold mb-3">
                    ✓ Currently Available
                </div>

                <a
                    href="{{ route('donor.request', $donor->id) }}"
                    class="btn btn-main"
                >
                    Request Blood From This Donor
                </a>

            @else

                <div class="text-danger fw-bold">
                    Currently Unavailable
                </div>

            @endif

        </div>

    </div>

</div>

</body>

</html>