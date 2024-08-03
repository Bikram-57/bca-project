<!DOCTYPE html>
<html>
<head>
    <title>New Support Ticket Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        p {
            line-height: 1.6;
        }
        .info {
            margin: 20px 0;
        }
        .info strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Support Ticket Created</h1>
        <p class="info"><strong>Created by:</strong> {{ $user_name }}</p>
        <p class="info"><strong>Subject:</strong> {{ $support->subject }}</p>
        <p class="info"><strong>Description:</strong> {{ $support->description }}</p>
        @if($support->attachment)
            <p class="info"><strong>Attachment:</strong> <a href="{{ url('assets/support_files/' . $support->attachment) }}" >View Attachment</a></p>
        @endif
        <p>Thank you.</p>
    </div>
</body>
</html>
