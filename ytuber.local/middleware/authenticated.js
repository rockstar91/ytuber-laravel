export default function ({ store, redirect }) {
  // If the user is not authenticated
  //if (!store.state.auth.user ||  localStorage.getItem('auth') == false) {

  if (!store.state.auth.loggedIn || !store.state.auth || store.state.auth.user.banned == 1) {
    console.log('not logged in');
    return redirect('/login')
  }
  else{
    console.log('you are logged in');
  }
}
