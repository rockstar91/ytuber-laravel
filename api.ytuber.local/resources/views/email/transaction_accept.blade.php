<h4>Здравствуйте, {{ $data->name }} подтвердите передачу поинтов на аккаунт: {{ $data->account_id }}</h4>
<p><strong>С вашего аккаунта:</strong> {{ $data->email }}</p> уйдёт {{ $data->points }}
<p>Если вы согласны перейдите по ссылке <a href="https://ytuber.ru/allow-transaction/{{ $data->id }}?hash={{ $data->hash }}" target="_blank">Подтвердить</a></p>