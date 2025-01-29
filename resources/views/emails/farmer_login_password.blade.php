@component('mail::message')
Dear {{ $message['name'] }},

Your account as a Farmer has been successfully created. Below are your login credentials:

 
**Email:** {{ $message['email'] }}  
**Contact:** {{ $message['contact'] }}  

**Password:** {{ $message['password'] }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent