import Echo from 'laravel-echo'
window.Pusher = require('pusher-js')

export default ({app}, inject) => {
    const echo = new Echo({
        broadcaster: 'pusher',
        key: '3Pb2350Br553cgYcxvvN2w213hZ',
        wsHost: 'api.ytuber.ru',
        wsPort: 6001,
        wssPort: 6001, // For SSL
        forceTLS: true,
        encrypted: true, // For SSL
        disableStats: true,
        authEndpoint:'https://api.ytuber.ru/api/broadcasting/auth',
        enabledTransports: ['ws', 'wss'],
        /*        authorizer: (channel, options) => {
                    return {
                        authorize: (socketId) => {
                            console.log(channel.name);
                            app.$axios
                                .$post('/api/broadcasting/auth', {
                                    socket_id: socketId,
                                    channel_name: channel.name,
                                })
                                .then((response) => {
                                    console.log('/api/broadcasting/auth - success');
                                })
                                .catch((error) => {
                                    console.log('/api/broadcasting/auth - error '+error);
                                })
                        },
                    }
                },*/
        /*       auth: {
                   headers: {
                       Authorization: 'Bearer 4|ennMN6dKzadrTMAj7V6GsgovJKO4BL7HCsjROVpa',
                       Accept: "application/json",
                   },
               }*/
    })

    inject('echo', echo)
}
