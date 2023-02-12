<h4>Смена пароля на сайте..</h4>

<p><strong>Имя:</strong> {{ $data->username }}</p>
<p><strong>Почта:</strong> {{ $data->email }}</p>
<p><strong>Вы можете сменить пароль аккаунта, пройдя по ссылке: </strong> <a href="https://api.ytuber.ru/api/reset-new-password?activate_hash={{ $data->message }}&email={{ $data->email }}" target="_blank">Сбросить пароль</a></p>