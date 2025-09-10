<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Nuevo mensaje</title>
  </head>
  <body>
    <h2>Nuevo mensaje desde el formulario de contacto</h2>
    <p><strong>Nombre:</strong> {{ $contact->name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    @if($contact->company)
      <p><strong>Empresa:</strong> {{ $contact->company }}</p>
    @endif
    @if($contact->phone)
      <p><strong>Teléfono:</strong> {{ $contact->phone }}</p>
    @endif
    @if($contact->service)
      <p><strong>Servicio:</strong> {{ $contact->service }}</p>
    @endif
    <p><strong>Mensaje:</strong></p>
    <p>{!! nl2br(e($contact->message)) !!}</p>
    <hr>
    <p>IP: {{ $contact->ip }}</p>
    <p>User-Agent: {{ $contact->user_agent }}</p>
  </body>
</html>
