// Provide nuxt-axios instance to use same configuration across the whole project
// I've used typical CRUD method names and actions here
export default $axios => authBroadcast({
    auth(payload) {
        return $axios.$post('/api/broadcasting/auth', payload)
            .then((response) => {
                console.log('auth broadcasting...')
                payload.callback(false, response.data)
        })
            .catch((error) => {
                payload.callback(true, error)
            })
    },
})
