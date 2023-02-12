<h4>Вы зарегистрировались на сайте..</h4>

<p><strong>Имя:</strong> <?php echo e($data->username); ?></p>
<p><strong>Почта:</strong> <?php echo e($data->email); ?></p>
<p><strong>Активируйте аккаунт пройдя по ссылке:</strong> <a href="https://api.ytuber.ru/api/activate-account?activate_hash=<?php echo e($data->message); ?>&email=<?php echo e($data->email); ?>" target="_blank">Активировать аккаунт</a></p><?php /**PATH /var/www/ytuber/data/www/ytuber-nuxt-laravel/api.ytuber.local/resources/views/email/register.blade.php ENDPATH**/ ?>