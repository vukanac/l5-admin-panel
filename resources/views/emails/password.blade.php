<!-- resources/views/emails/password.blade.php -->

Click here to reset your password: <br/>
<br/>
<a href="{{ url('password/reset/'.$token) }}"
>{{ url('password/reset/'.$token) }}</a><br/>
<br/>
Email sent by<br/>
Licence Manager<br/>
