<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .user-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .user-info p {
            margin: 5px 0;
        }
        .user-info strong {
            display: inline-block;
            width: 120px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        
        @if(isset($ssoUser))
            <div class="user-info">
                <h2>User Information from SSO</h2>
                <p><strong>Name:</strong> {{ $ssoUser->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $ssoUser->email ?? 'N/A' }}</p>
                <p><strong>Partner:</strong> {{ $ssoUser->partner ?? 'N/A' }}</p>
                <p><strong>Cost Center:</strong> {{ $ssoUser->cost_center ?? 'N/A' }}</p>
                <p><strong>Job Title:</strong> {{ $ssoUser->job_title ?? 'N/A' }}</p>
                <p><strong>Token Expires:</strong> {{ date('Y-m-d H:i:s', $ssoUser->exp ?? time()) }}</p>
            </div>
        @else
            <p>No user information available.</p>
        @endif
    </div>
</body>
</html>