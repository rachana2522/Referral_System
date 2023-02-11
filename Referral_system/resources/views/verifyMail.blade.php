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
    <p> please <a href ="{{ $data['url'] }}">Click here</a> to verify the mail</p>

    <p>Thank you</p>
    
</body>
</html>