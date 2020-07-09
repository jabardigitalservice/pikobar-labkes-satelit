export default ({ store, route, redirect }) => {
  let user = store.getters['auth/user']
  if (!user) {
    return redirect('/login')
  }
  let allow_role_id = route.meta[0].allow_role_id
  if (allow_role_id.indexOf(user.role_id) == -1) {
    return redirect('/error-role')
  }
}
