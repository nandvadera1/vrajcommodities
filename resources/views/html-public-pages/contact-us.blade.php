<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .contact-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .contact-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group label.required:after {
            content: "*";
            color: red;
        }
        .form-group input,
        .form-group textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            color: #333;
        }
        .form-group textarea {
            resize: vertical;
            height: 100px;
        }
        .form-group button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
        .toastr-header {
            background-color: #28a745 !important;
            color: #fff !important;
        }
    </style>
</head>
<body>
    <div class="contact-form">
        <h2>Contact Us</h2>
        <form id="contactForm">
            <div class="form-group">
                <label for="name" class="required">Name</label>
                <input type="text" id="name" name="name" placeholder="Your name">
                <div class="error-message" id="nameError"></div>
            </div>
            <div class="form-group">
                <label for="mobile" class="required">Mobile</label>
                <input type="text" id="mobile" name="mobile" placeholder="Your mobile number">
                <div class="error-message" id="mobileError"></div>
            </div>
            <div class="form-group">
                <label for="subject" class="required">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Subject">
                <div class="error-message" id="subjectError"></div>
            </div>
            <div class="form-group">
                <label for="message" class="required">Message</label>
                <textarea id="message" name="message" placeholder="Your message"></textarea>
                <div class="error-message" id="messageError"></div>
            </div>
            <div class="form-group">
                <button type="button" onclick="submitForm()">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        function submitForm() {
            var name = document.getElementById("name").value.trim();
            var mobile = document.getElementById("mobile").value.trim();
            var subject = document.getElementById("subject").value.trim();
            var message = document.getElementById("message").value.trim();

            // Reset error messages
            $(".error-message").text("");

            // Validate form fields
            var isValid = true;
            if (name === "") {
                $("#nameError").text("Name is required");
                isValid = false;
            }
            if (mobile === "") {
                $("#mobileError").text("Mobile is required");
                isValid = false;
            }
            if (subject === "") {
                $("#subjectError").text("Subject is required");
                isValid = false;
            }
            if (message === "") {
                $("#messageError").text("Message is required");
                isValid = false;
            }

            // If form is valid, show success message
            if (isValid) {
                toastr.success('Thank you for contacting us! We will get back to you soon.', '', { "timeOut": 3000, "extendedTimeOut": 0, "toastClass": "toastr-header" });
                // You can add your form submission logic here
            }
        }
    </script>
</body>
</html>
