<!DOCTYPE html>
<html lang="en">
<head>
    <title>Passowrd Reset</title>
    <style>
        body{
            background-color: #eee;
            padding: 100px 0 0;
            margin: 0;
        }
        #wrapper{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            text-align: center;
            width: 500px;
            padding: 30px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        #wrapper .box-head{
            text-align: center;
            margin: 0 0 40px;
        }

        #wrapper .box-head h4{
            color: #3E4E5F;
            font-size: 30px;
            font-weight: 600;
            margin: 0;
        }

        #wrapper .user{
            display: block;
        }

        #wrapper span,
        #wrapper p{
            color: #555;
            font-size: 17px;
            margin: 0 0 30px;
        }

        #wrapper a{
            color: #fff;
            background-color: #3E4E5F;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 30px;
            display: inline-block;
            transition: all 0.3s ease 0s;
        }

        #wrapper a:focus,
        #wrapper a:hover{
            color: #fff;
            background-color: #3E4E5F;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div class="box-head">
            <h4>{{site_settings()->site_name}}</h4>
            <h4>Reset your password</h4>
        </div>
        <span class="user">Hi, {{$data['user_name']}}</span>
        <p>We're sending you this email because you requested a password reset. Click on this link to create a new password.</p>
        <a href="{{$data['url']}}">Set a new password</a>
    </div>
</body>
</html>
