# Notifier Bundle

Notifier Bundle for the Symfony Framework.

## What is Notifier?

Sends notification to admin when exception occurs.
Notification is send only one for each unique exception.

## Getting started

Required environment variables
```dotenv
# App key from SMSLabs
SMS_NOTIFIER_APP_KEY=secret

# Secret key from SMSLabs
SMS_NOTIFIER_SECRET_KEY=secret

# Name of sender, must be set in SMSLabs
SMS_NOTIFIER_SENDER_ID="SMSLabs config"

# List of SMS notification receivers
SMS_NOTIFIER_RECEIVERS='["123456789", "987654321"]'

# Name of project for easy identification for admin
SMS_NOTIFIER_PROJECT_NAME="Demo project"

# Config of SMTP server from EmailLabs
EMAIL_NOTIFIER_SMTP_ACCOUNT="1.xyz.smtp"

# App key from EmailLabs
EMAIL_NOTIFIER_APP_KEY=secret

# Secret key from EmailLabs
EMAIL_NOTIFIER_SECRET_KEY=secret

# List of email notification receivers
EMAIL_NOTIFIER_RECEIVERS='["mail@mail.it", "demo@mail.it"]'
```

Removing lock from particular notification, because notification is send only once.
If you want to re-enable notifications for particular error, you need to unlock it with that command.
```shell script
bin/console notifier:remove-lock {identifier}
```
