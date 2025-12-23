@component('mail::message')
# Nouveau message du formulaire de contact

**Nom :** {{ $firstname ?? '' }}  
**Prénom :** {{ $lastname ?? '' }}  
**E-mail :** {{ $email }}

---

## Message :
{{ $message }}

@endcomponent
