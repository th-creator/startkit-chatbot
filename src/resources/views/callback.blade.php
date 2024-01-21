<html>
<head>
  <meta charset="utf-8">
  <title>Experts</title>
  <script>
    // localStorage.setItem('user',"{{$user}}")
    localStorage.setItem('access_token',"{{$token}}")
    window.location = "/auth/Home";
  </script>
</head>
<body>
  {{$token}}
</body>
</html>