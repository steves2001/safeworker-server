<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Confirm Registration</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #000;
                color: #fff;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }            
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Confirm Your Registration
                </div>

                <div class="links">
                    <p>Hi {{$userName}}</p>
                    <p>This is a confirmation email from Lincoln College study-safe, your registration has been processed please click the link below to accept the registration then login from your app.</p>
                    <a href="{{$apiRoute}}{{$userToken}}">Click this link to confirm your registration you will then be able to login to the app.</a>
                    <p>Regards</p>
                    <p>Lincoln College Admin</p>
                </div>
            </div>
        </div>
    </body>
</html>
