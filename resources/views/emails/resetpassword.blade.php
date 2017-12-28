<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reset Password</title>

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
                    Reset Your Password
                </div>

                <div class="links">
                    <p>Hi {{$userName}}</p>
                    <p>This is a password reset email from Lincoln College study-safe, your password has been set to {{$userPassword}} please click the link below to accept the change and then login from your app.</p>
                    <a href="{{$apiRoute}}{{$userToken}}">Click this link to confirm your new password you will then be able to login.</a>
                    <p>Once you have logged in you will be able to set your own password.</p>
                    <p>Regards</p>
                    <p>Lincoln College Admin</p>
                </div>
            </div>
        </div>
    </body>
</html>
