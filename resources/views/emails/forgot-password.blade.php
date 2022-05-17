<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Verify Your Email!</title>
</head>
<body>
    <div class="container bg-white p-5">
        <div>
            <h2>Hello <strong>{{ $user->name }}</strong>,</h2>
        </div>
        <div class="fs-3 mt-4">
            Your forgot password verify code is <strong>{{ $verify_code }}</strong>
        </div>
        <div class="mt-5">
            <p>Thank you.</p>
        </div>
    </div>
</body>
</html>
