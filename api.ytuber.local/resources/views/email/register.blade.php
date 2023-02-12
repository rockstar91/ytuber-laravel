<h4>Вы зарегистрировались на сайте..</h4>

<p><strong>Имя:</strong> {{ $data->username }}</p>
<p><strong>Почта:</strong> {{ $data->email }}</p>
<p><strong>Активируйте аккаунт пройдя по ссылке:</strong> <a href="https://api.ytuber.ru/api/activate-account?activate_hash={{ $data->message }}&email={{ $data->email }}" target="_blank">Активировать аккаунт</a></p>