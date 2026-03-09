<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Recovery</title>
</head>
<body>
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="border-collapse: collapse;">
        <tr>
            <td style="background-color: #f5f5f5; padding: 20px;">
                <h2 style="color: #333333;">Password Recovery</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <p style="font-size: 16px; line-height: 1.5;">Dear {{ $details["name"] }},</p>
                <p style="font-size: 16px; line-height: 1.5;">We received a request to reset your password. If you didn't make this request, please ignore this email.</p>
                <p style="font-size: 16px; line-height: 1.5;">To reset your password, click the following link:</p>
                <p style="font-size: 16px; line-height: 1.5;"><a href="{{ route('reset', ['id' => $details['data'] ]) }} " style="color: #007bff;">Reset Password</a></p>
                <p style="font-size: 16px; line-height: 1.5;">If the above link doesn't work, copy and paste the following URL into your browser:</p>
                <p style="font-size: 16px; line-height: 1.5;">{{ route('reset', ['id' => $details['data'] ]) }} </p>
                <p style="font-size: 16px; line-height: 1.5;">Thank you,</p>
                <p style="font-size: 16px; line-height: 1.5;">AboveMart</p>
            </td>
        </tr>
    </table>
</body>
</html>
