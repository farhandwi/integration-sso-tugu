<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 500px;
            padding: 30px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #e74c3c;
            margin-top: 0;
        }
        p {
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Access Denied</h1>
        
        @if(session('error'))
            <p class="error-message">{{ session('error') }}</p>
        @endif
        
        <p>You do not have permission to access this resource.</p>
        <p>Please ensure you're using a valid authentication token or contact the system administrator.</p>
        
        <a href="/" class="btn">Return to Home</a>
    </div>
</body>
</html>