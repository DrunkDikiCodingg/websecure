<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <h1 style='text-align: center; color: #4c83ee;'>Your Verification Code</h1>
    <p style='text-align: center;'>
        Hello <strong><?= htmlspecialchars($username) ?></strong> (<?= htmlspecialchars($email) ?>),
    </p>
    <p style='text-align: center;'>
        Use the following code to verify your login:
    </p>
    <div style='text-align: center; margin: 20px 0;'>
        <span style='font-size: 24px; font-weight: bold; color: #4c83ee; border: 2px solid #4c83ee; padding: 10px 20px; border-radius: 8px; display: inline-block;'>
            <?= htmlspecialchars($code) ?>
        </span>
    </div>
    <p style='text-align: center;'>
        <strong>Note:</strong> This code will expire in <span style='color: #FF0000; font-weight: bold;'>5 minutes</span>. Please do not share it with anyone.
    </p>
    <p style='text-align: center;'>
        If you did not attempt to log in, please ignore this email or contact our support team.
    </p>
    <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
    <p style='text-align: center; font-size: 12px; color: #555;'>
        Need help? Visit our <a href='#' style='color: #4c83ee; text-decoration: none;'>support page</a> or contact us directly.
    </p>
</div>
