<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$data['title']}}  </title>
</head>
<body>
   
    <p> Hii {{$data['name']}} , Welcome to referral system !</p>
    <p> <b> Username:-</b> {{$data['email'] }}</p>
    <p> <b> password:-</b> {{$data['password'] }}</p>
    <p> You can add users to  your network by share your <a href ="{{$data['url']}}">Referral Link</a></p>
    <p>Thank you</p>

</body>
</html>