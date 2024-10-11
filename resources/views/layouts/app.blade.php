<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/css/bootstrap5-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/js/bootstrap5-toggle.ecmas.min.js"></script>
    <style>
        /* General body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #dfdfdf;
            color: #333;
        }

        h2,
        h4 {
            font-weight: bold;
        }

        .container {
            margin-top: 30px;
        }

        /* Cards (pricing tables) */
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 0px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .card-text {
            font-size: 1rem;
            color: #6c757d;
        }

        /* Buttons */
        .btn-dark {
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 5px 10px;
        }

        /* Forms */
        form label {
            font-weight: 500;
            color: #495057;
        }

        form input {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            box-shadow: none;
            font-size: 0.95rem;
        }

        form input:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        form button {
            padding: 10px;
            font-size: 1rem;
            border-radius: 0.25rem;
            margin-top: 10px;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .card-title {
                font-size: 1.3rem;
            }

            .table-responsive {
                overflow-x: scroll;
            }

            table th,
            table td {
                font-size: 0.9rem;
            }

            form input {
                font-size: 0.9rem;
            }
        }

        .form-control:focus {
            border-color: #242424c5;
            box-shadow: 0 0 0 .25rem rgb(88 88 88 / 25%);
        }

        .form-control {
            border-color: #242424c5;
        }

        tr td,
        tr th {
            background-color: #fff;
        }

        .table {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: #242424c5;
        }

        .w-10 {
            width: 150px;
        }
    </style>
</head>

<body>

    {{ $slot }}

    <script>
        $('input[data-toggle="toggle"]').bootstrapToggle();
    </script>
</body>

</html>
