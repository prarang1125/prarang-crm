<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007BFF;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin: 10px 0;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            font-size: 16px;
            margin: 5px 0;
        }

        ul li strong {
            color: #007BFF;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to Prarang, {{ $request->firstName }}!</h1>
        <p>We're excited to have you join our platform. Below are your login details for accessing the Prarang CRM:</p>
        <ul>
            <li><strong>URL:</strong> <a href="https://crm-test.prarang.com"
                    target="_blank">https://crm-test.prarang.com</a>
            </li>
            <li><strong>Email:</strong> {{ $request->emailId }}</li>
            <li><strong>Password:</strong> {{ $request->empPassword }}</li>
        </ul>
        <p>Please keep your login credentials safe and secure. If you need any assistance, our team is always
            here to help.</p>
        <p>Feel free to contact us at <a href="mailto:query@prarang.in">query@prarang.in</a>.</p>
        <p>Welcome aboard, and happy exploring!</p>
        <p>Best regards,</p>
        <p><strong></strong><br>Prarang</p>
        <div class="footer">
            &copy; {{ date('Y') }} Prarang. All rights reserved.
        </div>
    </div>
</body>

</html>
