# PayQR
PayQr - A PHP QR Payment Library


Overview
PayQr is a PHP-based software tool that allows you to generate SEPA (Single Euro Payments Area) compliant QR codes for payment initiation. This library is designed to create QR codes for payment, storing all necessary details such as IBAN, BIC, recipient name, amount, and more.

Dependencies
QR-Library: The core functionality of PayQr relies on the QR-Library to generate QR codes. Ensure you have this library installed or included in your project.

Features
SEPA Compliant: Generates QR codes following the guidelines for SEPA payments.
Dynamic QR Image Size: Customize the size of the QR image based on your requirements.
Multiple Character Sets: Supports multiple character sets like UTF-8, ISO 8859-1, and more.
Detailed Exception Handling: Provides useful error messages for invalid inputs.
Customizable Payment Details: Set various payment attributes including amount, subject, comment, and more.
Flexible Versioning: Supports both version 1 and 2, with specific requirements for BIC.

1.Usage 

Initializatin
$payQr = new PayQr();


2. Set Payment Details
$payQr->setRecipientName("John Doe");
$payQr->setIban("YOUR_IBAN_HERE");
$payQr->setBic("YOUR_BIC_HERE");
$payQr->setAmount("50.00");
$payQr->setSubject("Invoice Payment");


3. Generate QR Code
$payQr->generate("/path/to/store/qr");


Author
Created by Tuncay Uyar.

