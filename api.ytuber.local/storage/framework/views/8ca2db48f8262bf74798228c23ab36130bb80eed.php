<h4>Здравствуйте, <?php echo e($data->name); ?> подтвердите передачу поинтов на аккаунт: <?php echo e($data->account_id); ?></h4>
<p><strong>С вашего аккаунта:</strong> <?php echo e($data->email); ?></p> уйдёт <?php echo e($data->points); ?>

<p>Если вы согласны перейдите по ссылке <a href="https://ytuber.ru/allow-transaction/<?php echo e($data->id); ?>?hash=<?php echo e($data->hash); ?>" target="_blank">Подтвердить</a></p><?php /**PATH /var/www/ytuber/data/www/ytuber-nuxt-laravel/api.ytuber.local/resources/views/email/transaction_accept.blade.php ENDPATH**/ ?>