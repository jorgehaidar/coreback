<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Occurred</title>
</head>
<body>
<h1>An error has occurred in the application</h1>
<p><strong>Error ID:</strong> {{ $errorData['error_id'] }}</p>
<p><strong>Message:</strong> {{ $errorData['message'] }}</p>
<p><strong>File:</strong> {{ $errorData['file'] }} (Line {{ $errorData['line'] }})</p>
<p><strong>Path:</strong> {{ $errorData['path'] }} (Method: {{ $errorData['method'] }})</p>
<p><strong>IP Address:</strong> {{ $errorData['ip'] }}</p>

@if (is_array($errorData['user']))
    <p><strong>User:</strong> {{ $errorData['user']['name'] }} (ID: {{ $errorData['user']['id'] }}, Email: {{ $errorData['user']['email'] }})</p>
@else
    <p><strong>User:</strong> Guest</p>
@endif

<h3>Payload:</h3>
<pre>{{ json_encode($errorData['payload'], JSON_PRETTY_PRINT) }}</pre>

<h3>Stack Trace:</h3>
<pre>{{ $errorData['trace'] }}</pre>

<p><strong>Additional Information:</strong></p>
<ul>
    <li><strong>Environment:</strong> {{ env('APP_ENV') }}</li>
    <li><strong>Timestamp:</strong> {{ now() }}</li>
</ul>
</body>
</html>
