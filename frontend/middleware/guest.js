export default ({ store, redirect }) => {
  console.log(store.getters)
  if (store.getters['auth/check']) {
    return redirect('/home')
  }
}
