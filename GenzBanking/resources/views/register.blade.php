<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
@if(session('message')) <p>{{ session('message') }}</p> @endif

<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
    @csrf
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="phone" placeholder="Phone" required><br>
    <input type="text" name="address" placeholder="Address"><br>
    <input type="file" name="document"><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" required><br>
    <input type="text" name="security_question" placeholder="Security Question"><br>
    <input type="text" name="security_answer" placeholder="Security Answer"><br>
    <button type="submit">Register</button>
</form>
</body>
</html>
