<!DOCTYPE html>
<html>
<head>
    <title>Login Admin Cafe</title>
</head>
<body style="margin:0; font-family:Arial; background:#f4f4f4;">

<div style="display:flex; height:100vh; justify-content:center; align-items:center;">

    <div style="width:320px; background:white; padding:25px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">

        <h2 style="text-align:center; margin-bottom:20px;">Login Admin</h2>

        <form method="POST" action="/login">

            @csrf

            <label>Username</label><br>
            <input type="text" name="username" placeholder="Masukkan username"
                style="width:100%; padding:10px; margin:8px 0 15px 0; border:1px solid #ccc; border-radius:6px;">

            <label>Password</label><br>
            <input type="password" name="password" placeholder="Masukkan password"
                style="width:100%; padding:10px; margin:8px 0 20px 0; border:1px solid #ccc; border-radius:6px;">

            <button type="submit"
                style="width:100%; padding:10px; background:#2c3e50; color:white; border:none; border-radius:6px; cursor:pointer;">
                Login
            </button>

        </form>

    </div>

</div>

</body>
</html>