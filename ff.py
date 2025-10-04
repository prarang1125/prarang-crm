import imaplib
import email
from email.header import decode_header
from email.utils import parsedate_to_datetime

# Your email and password
username = "rohit.kprarang@gmail.com"
password = "mdtgebvpenwxccor"  # Consider using OAuth 2.0 for security

# Connect to the Gmail IMAP server
mail = imaplib.IMAP4_SSL("imap.gmail.com")

# Log in to your Gmail account
mail.login(username, password)

# Select the 'Sent Mail' folder (use the correct folder name from the list command)
try:
    mail.select('"[Gmail]/Sent Mail"')  # Correct folder name, ensure the folder name has no extra spaces

    print("Successfully selected the folder.")

    # Search for all sent emails
    status, messages = mail.search(None, "ALL")  # You can change search criteria as needed

    if status == "OK":
        email_ids = messages[0].split()

        # Fetch and process sent emails
        for email_id in email_ids:
            # Fetch the email by ID
            status, msg_data = mail.fetch(email_id, "(RFC822)")

            # Process the fetched email
            for response_part in msg_data:
                if isinstance(response_part, tuple):
                    # Parse the email content
                    msg = email.message_from_bytes(response_part[1])

                    # Decode email subject
                    subject, encoding = decode_header(msg["Subject"])[0]
                    if isinstance(subject, bytes):
                        # If the subject is in bytes, decode it
                        subject = subject.decode(encoding if encoding else "utf-8")

                    # Decode the email sender (you in this case)
                    from_ = msg.get("From")

                    # Decode the recipient (who the email was sent to)
                    to_ = msg.get("To")

                    # Decode the email date (time)
                    date_ = msg.get("Date")
                    if date_:
                        # Parse and format the date (time)
                        date_time = parsedate_to_datetime(date_)
                    else:
                        date_time = "No Date Available"

                    # Extract the body content (email body)
                    body = ""
                    if msg.is_multipart():
                        for part in msg.walk():
                            # Extract plain text parts
                            if part.get_content_type() == "text/plain":
                                body = part.get_payload(decode=True).decode(part.get_content_charset(), errors='ignore')
                                break
                    else:
                        body = msg.get_payload(decode=True).decode(msg.get_content_charset(), errors='ignore')

                    # Print out email details
                    print(f"Subject: {subject}")
                    print(f"From: {from_}")
                    print(f"To: {to_}")
                    print(f"Date: {date_time}")
                    print(f"Body: {body[:500]}...")  # Only print the first 500 characters of the body for brevity
                    print("-" * 50)  # Separator for better readability

except Exception as e:
    print(f"Error selecting folder or fetching emails: {str(e)}")

# Logout and close the connection
mail.logout()
