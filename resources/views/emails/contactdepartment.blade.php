<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Student Enquiry</title>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="message">
                    <p>{{$userName}} has sent an enquiry</p>
                    <hr>
                    <p>{{$userMessage}}</p>
                    <hr>
                    <p>They have requested you contact them through the email address {{$userEmail}}.</p>
                    <p>Regards</p>
                    <p>Lincoln College - Study Safe Application</p>
                </div>
            </div>
        </div>
    </body>
</html>
