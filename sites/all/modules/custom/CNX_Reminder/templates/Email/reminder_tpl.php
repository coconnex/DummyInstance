<?php global $base_url; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        .main_email_container {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .email_container_child {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            padding: 20px;
            border-radius: 8px;
        }
        .email_container_header {
            text-align: left;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            border-bottom: 1px solid #dfdfdf;
        }
        .email_container_header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email_container_content {
            padding: 20px;
            border-bottom: 1px solid #dfdfdf;
        }
        .email_container_content h2 {
            color: #333333;
            font-size: 18px;
        }
        .email_container_content p {
            color: #555555;
            line-height: 1.6;
            margin: 14px 0;
        }
        .email_container_footer {
            text-align: center;
            color: #777777;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="main_email_container">

        <div class="email_container_child">
            <div class="email_container_header">
                <img src="<?php echo $vars['LOGOIMAGE']; ?>" style="max-width: 150px;">
            </div>
            <div class="email_container_content">
                <h2>Dear <?php echo $vars['NAME'];?>,</h2>

                <p>We hope this email finds you well.This is a friendly reminder that our event is just a few days away!</p>

                <p>Time is running out, and we wanted to remind you that you have still not yet submitted </p>

                <p><strong> <?php echo $vars['FORM_NAME']; ?> </strong> [ <i>Deadline Date: <?php echo $vars['DEADLINE_DATE']; ?></i> ]</p>

                <p>We want to ensure you have all the details to make your experience as smooth as possible.</p>

                <p>In case you need to contact us, please feel free to reach out to <?php echo $vars['CONTACTUSMAIL']; ?></p>

                <p>We look forward to seeing you soon!</p>

                <p>Best regards,</p>
                <p><?php echo $vars['EVENTNAME']; ?> Team</p>
            </div>
            <div class="email_container_footer">
                <p>&copy; <?php echo date('Y').' '. $vars['EVENTNAME'] ?>. All rights reserved.</p>
            </div>
        </div>

    </div>
</body>
</html>
