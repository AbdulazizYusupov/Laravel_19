<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @vite('resources/js/app.js')
    <style>
        .chat-container {
            margin-top: 50px;
        }

        .user-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .chat-window {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message-list {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .message-input {
            border-radius: 20px;
            padding: 10px 20px;
            width: 100%;
            border: 1px solid #ccc;
        }

        .btn-send {
            border-radius: 20px;
            padding: 10px 20px;
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-send:hover {
            background-color: #0056b3;
        }

        .message-item {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .message-sender {
            font-weight: bold;
        }

        .message-text {
            margin-left: 10px;
        }

        .user-name-display {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #ffffff;
            color: #333;
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 500;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu {
            right: 0;
            left: auto;
        }

        /* Dumaloq rasm */
        .user-image {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .dropdown-container {
            display: flex;
            align-items: center;
        }

        .dropdown-container .dropdown-toggle {
            display: flex;
            align-items: center;
        }

        .btn-secondary {
            border-radius: 50px;
        }
    </style>
</head>

<body>
    <div class="container-fluid chat-container">
        <div class="row">
            <div class="col-md-2">
                <div class="dropdown-container">
                    <div class="dropdown-container d-flex align-items-center">
                        @if (auth()->user()->image)
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" class="user-image"
                                alt="User Image">
                        @else
                            <span class="user-name-display">{{ auth()->user()->name }}</span>
                        @endif
                        <button class="btn btn-secondary dropdown-toggle ms-4" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                                <form method="get" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div><br>
                <h4 class="text-center mb-4">Chat Users</h4>
                <div class="list-group user-list">
                    @foreach ($users as $user)
                        <a href="{{ route('show', $user->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="user-name">{{ $user->name }}</span>
                                @if ($user->status == 0)
                                    <span class="badge bg-secondary rounded-pill">Offline</span>
                                @else
                                    <span class="badge bg-primary rounded-pill">Online</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-md-8 offset-md-1 chat-window">
                @if (isset($models))

                    <h3>{{ $bro->name }}</h3><br>

                    <form action="{{ route('create', $chat->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="text" class="form-control message-input"
                                placeholder="Write message..."><br>

                            <label for="fileInput" class="btn btn-outline-secondary" style="cursor: pointer;">
                                Upload File
                            </label>
                            <input type="file" name="file" id="fileInput" class="file-input">

                            <button type="submit" class="btn-send"
                                style="width: 50px; background-color: #007bff; border-radius: 5px; padding: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                    <path
                                        d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <ul class="list-group message-list" id="messageList">
                        @foreach ($models as $model)
                            <li class="message-item">
                                <span class="message-sender text-primary">{{ $model->sender }}:</span>
                                <span class="message-text">{{ $model->text }}</span>
                                @if ($model->file)
                                    <div class="file-preview">
                                        <a href="{{ asset('storage/' . $model->file) }}" target="_blank">Download
                                            File</a>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @if ($chat)
        <script>
            const chatId = @json($chat->id);
            const userId = @json(auth()->user()->name);
        </script>
    @else
        <p>No chat selected</p>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
