<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @vite('resources/js/app.js')
</head>

<body>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-3">
                <ul class="list-group">
                    @foreach ($users as $user)
                        <li class="list-group-item"><a href="{{ route('show', $user->id) }}">{{ $user->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-7 offset-1 ml-4">
                @if (isset($models))
                    <h3>{{ $bro->name }}</h3><br>
                    <form action="{{ route('create', $chat->id) }}" method="POST">
                        @csrf
                        <input type="text" name="text" class="form-control" placeholder="Message.."><br>
                        <input type="submit" class="btn btn-primary" value="Send">
                    </form><br>
                    <ul class="list-group" id="messageList">
                        @foreach ($models as $model)
                            <li><span class="text-primary">{{ $model->sender }}</span>: {{ $model->text }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <script>
        const chatId = @json($chat->id);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
